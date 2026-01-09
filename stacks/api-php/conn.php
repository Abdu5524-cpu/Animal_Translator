<?php
// Local XAMPP MySQL settings
$db_host   = '127.0.0.1';
$db_user   = "root";
$db_pass   = "";
$db_name   = "Animals";

// Connect to the database
$config = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($config->connect_error) {
    die("Connection failed: " . $config->connect_error);
}
$config->set_charset("utf8");
?>
