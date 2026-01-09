<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$username = $_GET['username'] ?? '';

if (empty($username)) {
    echo json_encode(["success" => false, "message" => "Username missing."]);
    exit();
}

require_once "conn.php"; // ✅ Use local DB connection

if ($config->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

$stmt = $config->prepare("SELECT animal, time_date FROM animals WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$purchases = [];
while ($row = $result->fetch_assoc()) {
    $purchases[] = $row;
}

echo json_encode(["success" => true, "purchases" => $purchases]);

$stmt->close();
$config->close();
?>
