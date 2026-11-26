<?php
// POST { animal } (Authorization: Bearer <token>) -> { success, purchase_id, time_date }
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/../config/auth.php';
require_method('POST');

$username = require_user();
$animal   = input('animal');

if ($animal === '') {
    json_out(['success' => false, 'message' => 'Missing animal.'], 400);
}
if (mb_strlen($animal) > 20) {
    json_out(['success' => false, 'message' => 'Animal name must be 20 characters or fewer.'], 400);
}

require_once __DIR__ . '/../config/db.php';

$stmt = $config->prepare('INSERT INTO animals (username, animal) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $animal);

if (!$stmt->execute()) {
    error_log('makePurchase failed: ' . $config->error);
    json_out(['success' => false, 'message' => 'Could not save the purchase.'], 500);
}
$purchaseId = $config->insert_id;
$stmt->close();

// Read the timestamp the database stored, not PHP's clock (they can be in different time zones).
$stmt = $config->prepare('SELECT time_date FROM animals WHERE purchase_id = ?');
$stmt->bind_param('i', $purchaseId);
$stmt->execute();
$stmt->bind_result($timeDate);
$stmt->fetch();
$stmt->close();
$config->close();

json_out([
    'success'     => true,
    'message'     => 'Purchase successful.',
    'purchase_id' => $purchaseId,
    'time_date'   => str_replace(' ', 'T', (string)$timeDate),
], 201);
