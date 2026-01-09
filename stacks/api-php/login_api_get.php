<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "conn.php"; // ✅ use your local XAMPP connection

$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';
$response = ['success' => false];

if (!empty($username) && !empty($password)) {
    $stmt = $config->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $response['success'] = true;
        $response['message'] = "Login OK.";
    } else {
        $response['message'] = "Invalid credentials.";
    }

    $stmt->close();
    $config->close();
} else {
    $response['message'] = "Missing credentials.";
}

echo json_encode($response);
