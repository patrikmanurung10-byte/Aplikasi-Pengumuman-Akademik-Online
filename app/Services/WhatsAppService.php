<?php

namespace App\Services;

/**
 * WhatsApp Service using Fonnte API
 * Service untuk mengirim pesan WhatsApp menggunakan API Fonnte
 */
class WhatsAppService
{
    private $apiUrl;
    private $token;
    
    public function __construct()
    {
        // Konfigurasi Fonnte API
        $this->apiUrl = 'https://api.fonnte.com/send';
        $this->token = $this->getToken();
    }
    
    /**
     * Get API Token from config
     */
    private function getToken()
    {
        // Ambil token dari environment atau config
        return $_ENV['FONNTE_TOKEN'] ?? getenv('FONNTE_TOKEN') ?? 'YOUR_FONNTE_TOKEN_HERE';
    }
    
    /**
     * Send WhatsApp message
     * 
     * @param string $phone Nomor WhatsApp (format: 628xxxxxxxxx)
     * @param string $message Pesan yang akan dikirim
     * @return array Response dari API
     */
    public function sendMessage($phone, $message)
    {
        try {
            // Format nomor telepon
            $phone = $this->formatPhoneNumber($phone);
            
            // Data untuk dikirim ke API
            $data = [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62'
            ];
            
            // Kirim request ke Fonnte API
            $response = $this->makeRequest($data);
            
            return [
                'success' => true,
                'data' => $response,
                'message' => 'Pesan berhasil dikirim'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal mengirim pesan WhatsApp'
            ];
        }
    }
    
    /**
     * Send OTP via WhatsApp
     * 
     * @param string $phone Nomor WhatsApp
     * @param string $otp Kode OTP
     * @param string $name Nama user (opsional)
     * @return array Response
     */
    public function sendOTP($phone, $otp, $name = '')
    {
        $greeting = $name ? "Halo {$name}," : "Halo,";
        
        $message = "{$greeting}\n\n";
        $message .= "🔐 *Kode OTP Reset Password*\n";
        $message .= "Sistem APAO Polibatam\n\n";
        $message .= "Kode OTP Anda: *{$otp}*\n\n";
        $message .= "⚠️ *PENTING:*\n";
        $message .= "• Kode berlaku selama 10 menit\n";
        $message .= "• Jangan bagikan kode ini kepada siapapun\n";
        $message .= "• Gunakan kode ini untuk reset password\n\n";
        $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.\n\n";
        $message .= "Terima kasih,\n";
        $message .= "*Tim APAO Polibatam*";
        
        return $this->sendMessage($phone, $message);
    }
    
    /**
     * Send password reset confirmation
     * 
     * @param string $phone Nomor WhatsApp
     * @param string $name Nama user
     * @return array Response
     */
    public function sendPasswordResetConfirmation($phone, $name = '')
    {
        $greeting = $name ? "Halo {$name}," : "Halo,";
        
        $message = "{$greeting}\n\n";
        $message .= "✅ *Password Berhasil Direset*\n";
        $message .= "Sistem APAO Polibatam\n\n";
        $message .= "Password Anda telah berhasil direset pada:\n";
        $message .= "📅 " . date('d/m/Y H:i:s') . "\n\n";
        $message .= "🔒 *Tips Keamanan:*\n";
        $message .= "• Gunakan password yang kuat\n";
        $message .= "• Jangan bagikan password kepada siapapun\n";
        $message .= "• Logout dari perangkat yang tidak dikenal\n\n";
        $message .= "Jika ini bukan Anda, segera hubungi administrator.\n\n";
        $message .= "Terima kasih,\n";
        $message .= "*Tim APAO Polibatam*";
        
        return $this->sendMessage($phone, $message);
    }
    
    /**
     * Format phone number for WhatsApp
     * 
     * @param string $phone Nomor telepon
     * @return string Nomor yang sudah diformat
     */
    private function formatPhoneNumber($phone)
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika tidak dimulai dengan 62, tambahkan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Make HTTP request to Fonnte API
     * 
     * @param array $data Data untuk dikirim
     * @return array Response dari API
     */
    private function makeRequest($data)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->token,
                'Content-Type: application/json'
            ],
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP Error: " . $httpCode);
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON Decode Error: " . json_last_error_msg());
        }
        
        return $decodedResponse;
    }
    
    /**
     * Test connection to Fonnte API
     * 
     * @return array Test result
     */
    public function testConnection()
    {
        try {
            // Use device endpoint to validate token
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/device',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => [
                    'Authorization: ' . $this->token
                ],
            ]);
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            
            if ($error) {
                return [
                    'success' => false,
                    'error' => "cURL Error: " . $error
                ];
            }
            
            $decodedResponse = json_decode($response, true);
            
            return [
                'success' => $httpCode === 200,
                'http_code' => $httpCode,
                'response' => $decodedResponse,
                'token_valid' => $httpCode === 200
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}