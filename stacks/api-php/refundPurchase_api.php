<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . "/conn.php";

$purchase_id = $_GET['purchase_id'] ?? '';
$response = ['success' => false];

if ($purchase_id === '') {
  $response['message'] = "Missing purchase_id.";
} else {
  $stmt = $config->prepare("DELETE FROM animals WHERE purchase_id = ?");
  $stmt->bind_param("i", $purchase_id);

  if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Purchase refunded.";
  } else {
    $response['message'] = "Error: " . $config->error;
  }

  $stmt->close();
}

$config->close();
echo json_encode($response);
