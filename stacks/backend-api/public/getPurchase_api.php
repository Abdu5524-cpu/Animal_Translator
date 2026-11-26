<?php
// GET (Authorization: Bearer <token>) -> { success, purchases: [{purchase_id, animal, time_date}] }
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/../config/auth.php';

// The username comes from the signed token, never from the request,
// so one user can no longer read another user's purchases.
$username = require_user();

require_once __DIR__ . '/../config/db.php';

$stmt = $config->prepare('
    SELECT purchase_id, animal, time_date
      FROM animals
     WHERE username = ?
  ORDER BY time_date DESC, purchase_id DESC
');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

$purchases = [];
while ($row = $result->fetch_assoc()) {
    $purchases[] = [
        'purchase_id' => (int)$row['purchase_id'],
        'animal'      => $row['animal'],
        // ISO 8601 so every JavaScript engine (including Hermes) can parse it.
        'time_date'   => str_replace(' ', 'T', (string)$row['time_date']),
    ];
}

$stmt->close();
$config->close();

json_out(['success' => true, 'purchases' => $purchases]);
