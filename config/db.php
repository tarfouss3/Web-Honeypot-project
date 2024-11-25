<?php
require_once __DIR__ . '/../src/loadEnv.php';

try {
    loadEnv('/home/killerb/server/.env');
} catch (Exception $e) {
    die($e->getMessage());
}

$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

