<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$username = $_GET['username'] ?? '';

if (empty($username)) {
    echo json_encode([
        "success" => false,
        "message" => "Username missing."
    ]);
    exit();
}

require_once __DIR__ . "/conn.php";  // ✅ Use local DB connection

if ($config->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]);
    exit();
}

// ← Include purchase_id in the result set
$stmt = $config->prepare("
    SELECT
        purchase_id,
        animal,
        time_date
    FROM animals
    WHERE username = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$purchases = [];
while ($row = $result->fetch_assoc()) {
    // Each $row now has ['purchase_id', 'animal', 'time_date']
    $purchases[] = [
        "purchase_id" => (int)$row["purchase_id"],
        "animal"      => $row["animal"],
        "time_date"   => $row["time_date"]
    ];
}

$stmt->close();
$config->close();

echo json_encode([
    "success"   => true,
    "purchases" => $purchases
]);
