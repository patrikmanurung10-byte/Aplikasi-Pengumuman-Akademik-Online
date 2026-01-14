<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $app_name : $app_name; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo \App\Core\View::asset('css/styles.css'); ?>">
    
    <style>
        body {
            background: linear-gradient(135deg, #e8f3ff, #f8faff);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        
        .logo {
            width: 120px;
            display: block;
            margin: 20px auto 10px;
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 600;
        }
        
        <?php if (isset($inline_css)): ?>
            <?php echo $inline_css; ?>
        <?php endif; ?>
    </style>
</head>
<body<?php echo isset($body_class) ? ' class="' . $body_class . '"' : ' class="d-flex align-items-center justify-content-center min-vh-100"'; ?>>

<?php 
// Show flash messages with SweetAlert2
$success = \App\Core\Session::getSuccess();
$error = \App\Core\Session::getError();
$warning = \App\Core\Session::getWarning();
$info = \App\Core\Session::getInfo();
?>

<!-- Main Content -->
<?php echo $content; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?php echo \App\Core\View::asset('js/main.js'); ?>"></script>

<!-- CSRF Token for AJAX requests -->
<script>
    window.csrfToken = '<?php echo \App\Core\Session::getCsrfToken(); ?>';
    window.baseUrl = '<?php echo \App\Core\View::url(); ?>';
</script>

<!-- SweetAlert2 Flash Messages -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show flash messages with SweetAlert2
    <?php if ($success): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?php echo addslashes($success); ?>',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
    
    <?php if ($error): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo addslashes($error); ?>',
            timer: 5000,
            showConfirmButton: true,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
    
    <?php if ($warning): ?>
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: '<?php echo addslashes($warning); ?>',
            timer: 4000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
    
    <?php if ($info): ?>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '<?php echo addslashes($info); ?>',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    <?php endif; ?>
});
</script>

</body>
</html>