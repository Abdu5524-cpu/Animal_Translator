<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . "/conn.php";

$username = $_GET['username'] ?? '';
$animal   = $_GET['animal']   ?? '';
$response = ['success' => false];

if ($username === '' || $animal === '') {
    $response['message'] = "Missing username or animal.";
} else {
    // Insert a new purchase
    $stmt = $config->prepare(
        "INSERT INTO animals (username, animal) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $username, $animal);

    if ($stmt->execute()) {
        $response['success']     = true;
        $response['message']     = "Purchase successful.";
        $response['purchase_id'] = $config->insert_id;
        $response['time_date']   = date('Y-m-d H:i:s');
    } else {
        $response['message'] = "Error: " . $config->error;
    }
    $stmt->close();
}

$config->close();
echo json_encode($response);
