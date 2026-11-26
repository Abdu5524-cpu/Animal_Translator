<?php
// POST { purchase_id } (Authorization: Bearer <token>) -> { success, message }
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/../config/auth.php';
require_method('POST');

$username = require_user();
$id       = filter_var(input('purchase_id'), FILTER_VALIDATE_INT);

if ($id === false) {
    json_out(['success' => false, 'message' => 'Missing purchase_id.'], 400);
}

require_once __DIR__ . '/../config/db.php';

// "AND username = ?" makes sure people can only refund their own purchases.
$stmt = $config->prepare('DELETE FROM animals WHERE purchase_id = ? AND username = ?');
$stmt->bind_param('is', $id, $username);
$stmt->execute();
$deleted = $stmt->affected_rows;
$stmt->close();
$config->close();

$deleted > 0
    ? json_out(['success' => true, 'message' => 'Purchase refunded.'])
    : json_out(['success' => false, 'message' => 'Purchase not found.'], 404);
