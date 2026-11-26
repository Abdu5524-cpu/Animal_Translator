<?php
// Lightweight liveness check for the host and the keep-awake ping. No database work.
header('Content-Type: application/json');
header('Cache-Control: no-store');
echo json_encode(['ok' => true]);
