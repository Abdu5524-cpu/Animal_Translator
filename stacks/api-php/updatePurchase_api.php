<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . "/conn.php";

$id     = $_GET['purchase_id'] ?? '';
$animal = $_GET['animal']      ?? '';
$response = ['success' => false];

if ($id === '' || $animal === '') {
    $response['message'] = "Missing purchase_id or animal.";
} else {
    $stmt = $config->prepare("
        UPDATE animals
           SET animal = ?
         WHERE purchase_id = ?
    ");
    $stmt->bind_param("si", $animal, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Purchase updated.";
    } else {
        $response['message'] = "Error: " . $config->error;
    }
    $stmt->close();
}

$config->close();
echo json_encode($response);
