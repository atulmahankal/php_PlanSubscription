<?php
// Check if config.php exists
if (!file_exists('config.php')) {
  header('Location: setup.php');
}

// Include the config.php file after the check
include 'config.php';

try {
    // Create a new MySQLi connection with error reporting
    $conn = new mysqli($host, $user, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        throw new Exception("Database connection failed. Please check your credentials and try again.");
    }
} catch (Exception $e) {
    // Handle the exception and show a custom error message
    die($e->getMessage());
}

// Error handling and other configurations
if ($envirnment == 'local') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}

ini_set('log_errors', '1');
ini_set('error_log', 'error.log');

date_default_timezone_set('Asia/Kolkata');
?>
