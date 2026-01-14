<?php

/**
 * Entry Point for APAO Polibatam
 * Front controller untuk aplikasi
 */

// Define debug mode
define('DEBUG', true);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include bootstrap
$app = require_once __DIR__ . '/../bootstrap.php';

// Run the application
$app->run();