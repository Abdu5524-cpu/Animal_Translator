<?php
// Database connection.
// Credentials come from environment variables so they never live in git.
// Local XAMPP defaults are used when a variable is not set.
$db_host = getenv('DB_HOST') ?: '127.0.0.1';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db_name = getenv('DB_NAME') ?: 'Animals';
$db_port = (int)(getenv('DB_PORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_OFF);
$config = @new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($config->connect_error) {
    error_log('DB connection failed: ' . $config->connect_error);
    http_response_code(500);
    // Answer in the format the caller expects, and never leak the driver error.
    $wantsJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')
        || str_ends_with(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '_api.php')
        || str_contains($_SERVER['SCRIPT_NAME'] ?? '', '_api');
    if ($wantsJson) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database unavailable.']);
    } else {
        echo 'The service is temporarily unavailable. Please try again later.';
    }
    exit();
}

$config->set_charset('utf8mb4');
