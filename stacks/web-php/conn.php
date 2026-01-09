<?php
$host = "localhost";      // Local MySQL server
$username = "root";       // Default XAMPP username
$password = "";           // Default XAMPP password = empty
$dbname = "Animals";      // The database you created in phpMyAdmin

// Connect to the database
$config = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($config->connect_error) {
    die("Connection failed: " . $config->connect_error);
}

$config->set_charset("utf8");
?>
