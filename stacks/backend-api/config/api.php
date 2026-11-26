<?php
// Shared helpers for the JSON API endpoints: CORS, JSON output, input parsing.

// Allowed browser origins, comma separated (e.g. "https://abdu5524-cpu.github.io").
// "*" is fine because auth uses a bearer token, not cookies.
$allowedOrigin = getenv('CORS_ORIGIN') ?: '*';

header('Access-Control-Allow-Origin: ' . $allowedOrigin);
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept');
header('Content-Type: application/json');

// Browsers send a preflight OPTIONS request before any request that carries
// an Authorization header. Answer it and stop.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit();
}

function json_out(array $body, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($body);
    exit();
}

/** Read a field from a JSON body, a form body, or (for non secret fields) the query string. */
function input(string $key, bool $allowQuery = true): string
{
    static $json = null;
    if ($json === null) {
        $raw = file_get_contents('php://input');
        $decoded = $raw ? json_decode($raw, true) : null;
        $json = is_array($decoded) ? $decoded : [];
    }
    $value = $json[$key] ?? $_POST[$key] ?? ($allowQuery ? ($_GET[$key] ?? '') : '');
    return is_string($value) ? trim($value) : (is_scalar($value) ? (string)$value : '');
}

function require_method(string $method): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== $method) {
        header('Allow: ' . $method);
        json_out(['success' => false, 'message' => "Use $method for this endpoint."], 405);
    }
}
