<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Inputs
$username = $_GET['username']         ?? '';
$password = $_GET['password']         ?? '';
$confirm  = $_GET['confirm_password'] ?? '';

$response = [
  'success' => false,
  'debug'   => []
];

// Echo what we received
$response['debug'][] = "Got username='{$username}'";

// Validate
if ($password !== $confirm) {
  $response['debug'][] = 'Passwords mismatch';
  $response['message'] = "Passwords do not match.";
} elseif ($username === '' || $password === '') {
  $response['debug'][] = 'Missing fields';
  $response['message'] = "Missing username or password.";
} else {
  require_once __DIR__ . "/conn.php";
  $response['debug'][] = "Connected to DB: " . $config->host_info;

  // Dump all rows in users
  $all = [];
  $res = $config->query("SELECT username FROM users");
  while ($r = $res->fetch_assoc()) {
    $all[] = $r['username'];
  }
  $response['debug'][] = "All users in table: " . json_encode($all);

  // Now check existence
  $stmt = $config->prepare("SELECT username FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();
  $count = $stmt->num_rows;
  $response['debug'][] = "Found rows matching '{$username}': {$count}";

  if ($count > 0) {
    $response['message'] = "Username already exists.";
  } else {
    $stmt = $config->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $username, $hashed);
    if ($stmt->execute()) {
      $response['success'] = true;
      $response['message'] = "Registration successful.";
    } else {
      $response['debug'][] = "Insert error: " . $config->error;
      $response['message'] = "Error creating user.";
    }
  }

  $stmt->close();
  $config->close();
}

echo json_encode($response, JSON_PRETTY_PRINT);
