<?php
// Minimal HS256 JSON Web Tokens with no external dependencies.
// The signing secret comes from the JWT_SECRET environment variable.

const JWT_TTL_SECONDS = 60 * 60 * 24 * 7; // 7 days

function jwt_secret(): string
{
    $secret = getenv('JWT_SECRET');
    if ($secret === false || strlen($secret) < 32) {
        error_log('JWT_SECRET is missing or shorter than 32 characters.');
        json_out(['success' => false, 'message' => 'Server misconfigured.'], 500);
    }
    return $secret;
}

function b64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function b64url_decode(string $data): string|false
{
    return base64_decode(strtr($data, '-_', '+/'), true);
}

function jwt_issue(string $username): string
{
    $header  = b64url_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $now     = time();
    $payload = b64url_encode(json_encode(['sub' => $username, 'iat' => $now, 'exp' => $now + JWT_TTL_SECONDS]));
    $sig     = b64url_encode(hash_hmac('sha256', "$header.$payload", jwt_secret(), true));
    return "$header.$payload.$sig";
}

/** Returns the username inside a valid token, or null. */
function jwt_verify(string $token): ?string
{
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return null;
    }
    [$header, $payload, $sig] = $parts;

    $headerData = json_decode((string)b64url_decode($header), true);
    if (($headerData['alg'] ?? '') !== 'HS256') {
        return null; // reject "none" and any other algorithm
    }

    $expected = b64url_encode(hash_hmac('sha256', "$header.$payload", jwt_secret(), true));
    if (!hash_equals($expected, $sig)) {
        return null;
    }

    $data = json_decode((string)b64url_decode($payload), true);
    if (!is_array($data) || !isset($data['sub'], $data['exp']) || $data['exp'] < time()) {
        return null;
    }
    return (string)$data['sub'];
}

function bearer_token(): string
{
    // Apache and some CGI setups hide the Authorization header in different places.
    $header = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? (function_exists('getallheaders') ? (array_change_key_case(getallheaders())['authorization'] ?? '') : '');
    return preg_match('/^Bearer\s+(\S+)$/i', (string)$header, $m) ? $m[1] : '';
}

/** Stops the request with 401 unless a valid token is present. Returns the username. */
function require_user(): string
{
    $username = jwt_verify(bearer_token());
    if ($username === null) {
        json_out(['success' => false, 'message' => 'Please log in again.'], 401);
    }
    return $username;
}
