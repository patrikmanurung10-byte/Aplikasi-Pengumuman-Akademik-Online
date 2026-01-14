<?php

/**
 * Routes Definition for APAO Polibatam
 * Definisi routing untuk aplikasi
 */

use App\Core\Application;

$app = Application::getInstance();

// Home routes
$app->addRoute('GET', '/', 'HomeController@index');
$app->addRoute('GET', '/home', 'HomeController@index');
$app->addRoute('GET', '/about', 'HomeController@about');
$app->addRoute('GET', '/contact', 'HomeController@contact');
$app->addRoute('GET', '/privacy', 'HomeController@privacy');
$app->addRoute('GET', '/terms', 'HomeController@terms');

// Authentication routes
$app->addRoute('GET', '/login', 'AuthController@showLogin');
$app->addRoute('POST', '/login', 'AuthController@login');
$app->addRoute('GET', '/register', 'AuthController@showRegister');
$app->addRoute('POST', '/register', 'AuthController@register');
$app->addRoute('GET', '/logout', 'AuthController@logout');
$app->addRoute('POST', '/logout', 'AuthController@logout');

// Forgot Password routes
$app->addRoute('GET', '/forgot-password', 'ForgotPasswordController@index');
$app->addRoute('POST', '/forgot-password/send-otp', 'ForgotPasswordController@sendOtp');
$app->addRoute('GET', '/forgot-password/verify', 'ForgotPasswordController@verify');
$app->addRoute('POST', '/forgot-password/verify-otp', 'ForgotPasswordController@verifyOtp');
$app->addRoute('GET', '/forgot-password/resend-otp', 'ForgotPasswordController@resendOtp');
$app->addRoute('GET', '/forgot-password/reset', 'ForgotPasswordController@reset');
$app->addRoute('POST', '/forgot-password/update-password', 'ForgotPasswordController@updatePassword');

// Dashboard routes (role-based routing)
$app->addRoute('GET', '/dashboard', 'DashboardController@index');

// Student routes
$app->addRoute('GET', '/student/dashboard', 'StudentController@dashboard');
$app->addRoute('GET', '/student/announcements', 'StudentController@announcements');
$app->addRoute('GET', '/student/announcements/{id}', 'StudentController@announcementDetail');
$app->addRoute('GET', '/student/profile', 'StudentController@profile');
$app->addRoute('POST', '/student/profile/update', 'StudentController@updateProfile');

// Dosen routes
$app->addRoute('GET', '/dosen/dashboard', 'DosenController@dashboard');
$app->addRoute('GET', '/dosen/announcements', 'DosenController@announcements');
$app->addRoute('GET', '/dosen/announcements/create', 'DosenController@createAnnouncement');
$app->addRoute('POST', '/dosen/announcements/store', 'DosenController@storeAnnouncement');
$app->addRoute('GET', '/dosen/announcements/{id}/edit', 'DosenController@editAnnouncement');
$app->addRoute('POST', '/dosen/announcements/{id}/update', 'DosenController@updateAnnouncement');
$app->addRoute('POST', '/dosen/announcements/{id}/delete', 'DosenController@deleteAnnouncement');
$app->addRoute('GET', '/dosen/profile', 'DosenController@profile');
$app->addRoute('POST', '/dosen/profile/update', 'DosenController@updateProfile');

// Admin routes (hanya admin yang bisa akses user management)
$app->addRoute('GET', '/admin/dashboard', 'AdminController@dashboard');
$app->addRoute('GET', '/admin/profile', 'AdminController@profile');
$app->addRoute('POST', '/admin/profile/update', 'AdminController@updateProfile');

// User Management routes
$app->addRoute('GET', '/admin/users', 'AdminController@users');
$app->addRoute('GET', '/admin/users/create', 'AdminController@createUser');
$app->addRoute('POST', '/admin/users/store', 'AdminController@storeUser');
$app->addRoute('GET', '/admin/users/{id}', 'AdminController@showUser');
$app->addRoute('GET', '/admin/users/{id}/details', 'AdminController@getUserDetails');
$app->addRoute('GET', '/admin/users/{id}/edit', 'AdminController@editUser');
$app->addRoute('POST', '/admin/users/{id}/update', 'AdminController@updateUser');
$app->addRoute('POST', '/admin/users/{id}/delete', 'AdminController@deleteUser');

// Announcement Management routes
$app->addRoute('GET', '/admin/announcements', 'AdminController@announcements');
$app->addRoute('GET', '/admin/announcements/create', 'AdminController@createAnnouncement');
$app->addRoute('POST', '/admin/announcements/store', 'AdminController@storeAnnouncement');
$app->addRoute('GET', '/admin/announcements/{id}/edit', 'AdminController@editAnnouncement');
$app->addRoute('POST', '/admin/announcements/{id}/update', 'AdminController@updateAnnouncement');
$app->addRoute('POST', '/admin/announcements/{id}/delete', 'AdminController@deleteAnnouncement');

// Settings routes
$app->addRoute('GET', '/settings', 'SettingsController@index');
$app->addRoute('POST', '/settings', 'SettingsController@updatePreferences');
$app->addRoute('GET', '/settings/privacy', 'SettingsController@privacy');
$app->addRoute('POST', '/settings/privacy', 'SettingsController@updatePrivacy');
$app->addRoute('GET', '/settings/security', 'SettingsController@security');
$app->addRoute('POST', '/settings/security', 'SettingsController@updateSecurity');

// Help routes
$app->addRoute('GET', '/help', 'HelpController@index');
$app->addRoute('GET', '/help/contact', 'HelpController@contact');
$app->addRoute('POST', '/help/contact', 'HelpController@submitContact');
$app->addRoute('GET', '/help/user-guide', 'HelpController@userGuide');
$app->addRoute('GET', '/help/system-info', 'HelpController@systemInfo');

// Error routes
$app->addRoute('GET', '/404', function() {
    http_response_code(404);
    \App\Core\View::render('errors/404', ['page_title' => 'Halaman Tidak Ditemukan'], null);
});

$app->addRoute('GET', '/403', function() {
    http_response_code(403);
    \App\Core\View::render('errors/403', ['page_title' => 'Akses Ditolak'], null);
});

$app->addRoute('GET', '/500', function() {
    http_response_code(500);
    \App\Core\View::render('errors/500', ['page_title' => 'Server Error'], null);
});