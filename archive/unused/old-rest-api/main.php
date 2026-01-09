<?php

echo "Index file is loading...<br>";

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/inc/config.php";
require_once __DIR__ . "/Controller/Api/BaseController.php";
require_once __DIR__ . "/Controller/Api/AnimalController.php";
require_once __DIR__ . "/Controller/Api/UserController.php";

$endpoint = $_GET['endpoint'] ?? '';
$action = $_GET['action'] ?? '';

if ($endpoint === 'animal') {
    $controller = new AnimalController();
} elseif ($endpoint === 'user') {
    $controller = new UserController();
} else {
    echo json_encode(["error" => "Invalid endpoint"]);
    exit();
}

$method = $action . 'Action';

if (method_exists($controller, $method)) {
    $controller->$method();
} else {
    echo json_encode(["error" => "Invalid action"]);
    exit();
}
