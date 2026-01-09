<?php
session_start(); // Required for any use of $_SESSION

// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Allow CORS (for frontend integration)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Load configuration and bootstrap logic
require __DIR__ . "/inc/config.php";
require __DIR__ . "/inc/bootstrap.php";

// Initialize DB connection
$config = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
if ($config->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $config->connect_error]);
    exit;
}

// Read parameters from URL
$endpoint = $_GET['endpoint'] ?? null;
$action = $_GET['action'] ?? null;

// Validate required parameters
if (!$endpoint || !$action) {
    http_response_code(400);
    echo json_encode(["error" => "Missing endpoint or action"]);
    exit();
}

// Load appropriate controllers
require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
require PROJECT_ROOT_PATH . "/Controller/Api/AnimalController.php";

// Determine controller and method
$className = ucfirst($endpoint) . "Controller";
$method = $action . "Action";

// Validate existence
if (!class_exists($className) || !method_exists($className, $method)) {
    http_response_code(404);
    echo json_encode(["error" => "Invalid endpoint or action"]);
    exit();
}

// Execute controller method
$controller = new $className();
$controller->{$method}();
