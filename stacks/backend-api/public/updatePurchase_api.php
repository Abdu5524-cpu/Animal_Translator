<?php
// POST { purchase_id, animal } (Authorization: Bearer <token>) -> { success, message }
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/../config/auth.php';
require_method('POST');

$username = require_user();
$id       = filter_var(input('purchase_id'), FILTER_VALIDATE_INT);
$animal   = input('animal');

if ($id === false || $animal === '') {
    json_out(['success' => false, 'message' => 'Missing purchase_id or animal.'], 400);
}
if (mb_strlen($animal) > 20) {
    json_out(['success' => false, 'message' => 'Animal name must be 20 characters or fewer.'], 400);
}

require_once __DIR__ . '/../config/db.php';

// Check ownership first: affected_rows is 0 both for "not yours" and for
// "same animal as before", so it can't tell those apart on its own.
$stmt = $config->prepare('SELECT 1 FROM animals WHERE purchase_id = ? AND username = ?');
$stmt->bind_param('is', $id, $username);
$stmt->execute();
$stmt->store_result();
$owned = $stmt->num_rows > 0;
$stmt->close();

if (!$owned) {
    $config->close();
    json_out(['success' => false, 'message' => 'Purchase not found.'], 404);
}

$stmt = $config->prepare('UPDATE animals SET animal = ? WHERE purchase_id = ? AND username = ?');
$stmt->bind_param('sis', $animal, $id, $username);
$ok = $stmt->execute();
$stmt->close();
$config->close();

$ok
    ? json_out(['success' => true, 'message' => 'Purchase updated.'])
    : json_out(['success' => false, 'message' => 'Could not update the purchase.'], 500);
