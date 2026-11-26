<?php
// POST (no body) -> { success, token, username }
// One tap demo: resets the demo account's sample purchases and signs in as it.
require_once __DIR__ . '/../config/api.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/demo.php';
require_method('POST');

require_once __DIR__ . '/../config/db.php';

try {
    demo_reset($config);
} catch (Throwable $e) {
    error_log('Demo reset failed: ' . $e->getMessage());
    json_out(['success' => false, 'message' => 'The demo is unavailable right now.'], 500);
}
$config->close();

json_out([
    'success'  => true,
    'message'  => 'Signed in to the demo account.',
    'token'    => jwt_issue(DEMO_USERNAME),
    'username' => DEMO_USERNAME,
]);
