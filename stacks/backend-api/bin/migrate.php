<?php
// Runs at container start: creates the tables if they don't exist and seeds the demo account.
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
