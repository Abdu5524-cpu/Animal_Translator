<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';
$confirm = $_GET['confirm_password'] ?? '';

$response = ['success' => false];

if ($password !== $confirm) {
    $response['message'] = "Passwords do not match.";
} elseif (!empty($username) && !empty($password)) {
    require_once "conn.php";

    if ($config->connect_error) {
        $response['message'] = "Database connection failed.";
    } else {
        $stmt = $config->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response['message'] = "Username already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $config->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Registration successful.";
            } else {
                $response['message'] = "Error creating user.";
            }
        }

        $stmt->close();
        $config->close();
    }
} else {
    $response['message'] = "Missing username or password.";
}

echo json_encode($response);
