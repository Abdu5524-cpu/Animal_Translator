<?php
// Runs at container start: creates the database and tables if they don't exist,
// then seeds the demo account. Safe to run on every start.

// Create the database first, so a fresh TiDB Cloud instance needs no manual setup.
$name = getenv('DB_NAME') ?: 'Animals';
if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
    fwrite(STDERR, "DB_NAME may only hold letters, digits and underscores.\n");
    exit(1);
}
$bootstrap = mysqli_init();
$flags = 0;
if (filter_var(getenv('DB_SSL'), FILTER_VALIDATE_BOOLEAN)) {
    $bootstrap->ssl_set(null, null, getenv('DB_SSL_CA') ?: '/etc/ssl/certs/ca-certificates.crt', null, null);
    $flags = MYSQLI_CLIENT_SSL;
}
mysqli_report(MYSQLI_REPORT_OFF);
if (!@$bootstrap->real_connect(getenv('DB_HOST') ?: '127.0.0.1', getenv('DB_USER') ?: 'root',
        getenv('DB_PASS') !== false ? getenv('DB_PASS') : '', null, (int)(getenv('DB_PORT') ?: 3306), null, $flags)
    || !$bootstrap->query("CREATE DATABASE IF NOT EXISTS `$name`")) {
    fwrite(STDERR, 'Could not create the database: ' . ($bootstrap->connect_error ?: $bootstrap->error) . "\n");
    exit(1);
}
$bootstrap->close();

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../config/demo.php';

$sql = file_get_contents(__DIR__ . '/../schema.sql');
foreach (array_filter(array_map('trim', explode(';', preg_replace('/^--.*$/m', '', $sql)))) as $statement) {
    if (!$config->query($statement)) {
        fwrite(STDERR, "Migration failed: {$config->error}\n");
        exit(1);
    }
}
demo_reset($config);
echo "Database ready, demo account seeded.\n";
