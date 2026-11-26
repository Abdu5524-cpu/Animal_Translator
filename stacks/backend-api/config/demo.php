<?php
// The shared demo account recruiters sign into with one tap.
// Its purchases are reset to this sample list on every demo login,
// so whatever one visitor edits or refunds never breaks the next visit.

const DEMO_USERNAME = 'demo';

const DEMO_PURCHASES = [
    // [animal, days ago]
    ['Dog', 21],
    ['Cat', 9],
    ['Horse', 4],
    ['Bird', 1],
];

function demo_reset(mysqli $db): void
{
    $db->begin_transaction();

    // The demo user can only be signed into through demo_login_api.php,
    // so its password is random and never shown anywhere.
    $hash = password_hash(bin2hex(random_bytes(24)), PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT IGNORE INTO users (username, password) VALUES (?, ?)');
    $user = DEMO_USERNAME;
    $stmt->bind_param('ss', $user, $hash);
    $stmt->execute();
    $stmt->close();

    $stmt = $db->prepare('DELETE FROM animals WHERE username = ?');
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $stmt->close();

    $stmt = $db->prepare('INSERT INTO animals (username, animal, time_date) VALUES (?, ?, ?)');
    foreach (DEMO_PURCHASES as [$animal, $daysAgo]) {
        $when = gmdate('Y-m-d H:i:s', time() - $daysAgo * 86400);
        $stmt->bind_param('sss', $user, $animal, $when);
        $stmt->execute();
    }
    $stmt->close();

    $db->commit();
}
