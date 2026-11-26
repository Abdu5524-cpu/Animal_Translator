<?php
// Credentials come from the environment; never commit real values.
define("DB_HOST", getenv('DB_HOST') ?: 'localhost');
define("DB_USERNAME", getenv('DB_USER') ?: 'root');
define("DB_PASSWORD", getenv('DB_PASS') ?: '');
define("DB_DATABASE_NAME", getenv('DB_NAME') ?: 'Animals');
