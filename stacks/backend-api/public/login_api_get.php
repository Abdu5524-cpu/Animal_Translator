<?php
// POST { username, password } -> { success, token, username }
// (File name kept for compatibility; credentials are no longer accepted in the URL.)
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/../config/auth.php';
require_method('POST');

$username = input('username', false);
$password = input('password', false);

if ($username === '' || $password === '') {
    json_out(['success' => false, 'message' => 'Missing credentials.'], 400);
}

require_once __DIR__ . '/../config/db.php';

$stmt = $config->prepare('SELECT password FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($hashed_password);
$found = $stmt->fetch();
$stmt->close();
$config->close();

if (!$found || !password_verify($password, $hashed_password)) {
    json_out(['success' => false, 'message' => 'Invalid credentials.'], 401);
}

json_out([
    'success'  => true,
    'message'  => 'Login OK.',
    'token'    => jwt_issue($username),
    'username' => $username,
]);
