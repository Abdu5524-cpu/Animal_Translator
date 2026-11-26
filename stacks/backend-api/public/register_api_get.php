<?php
// POST { username, password, confirm_password } -> { success, message }
// (File name kept for compatibility; credentials are no longer accepted in the URL.)
require_once __DIR__ . '/../config/api.php';
require_method('POST');

$username = input('username', false);
$password = input('password', false);
$confirm  = input('confirm_password', false);

if ($username === '' || $password === '') {
    json_out(['success' => false, 'message' => 'Missing username or password.'], 400);
}
if (!preg_match('/^[A-Za-z0-9_.-]{3,20}$/', $username)) {
    json_out(['success' => false, 'message' => 'Username must be 3 to 20 letters, numbers, dots, dashes or underscores.'], 400);
}
if (strlen($password) < 8) {
    json_out(['success' => false, 'message' => 'Password must be at least 8 characters.'], 400);
}
if ($password !== $confirm) {
    json_out(['success' => false, 'message' => 'Passwords do not match.'], 400);
}

require_once __DIR__ . '/../config/db.php';

$stmt = $config->prepare('SELECT 1 FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
$exists = $stmt->num_rows > 0;
$stmt->close();

if ($exists) {
    $config->close();
    json_out(['success' => false, 'message' => 'Username already exists.'], 409);
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $config->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $hashed);
$ok = $stmt->execute();
if (!$ok) {
    error_log('Register insert failed: ' . $config->error);
}
$stmt->close();
$config->close();

$ok
    ? json_out(['success' => true, 'message' => 'Registration successful.'], 201)
    : json_out(['success' => false, 'message' => 'Error creating user.'], 500);
