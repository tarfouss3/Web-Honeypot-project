<?php
session_start();

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logout() {
    require_once '../config/db.php';
    require_once '../vendor/autoload.php';

    $log = new Logger('auth');
    $log->pushHandler(new StreamHandler(__DIR__ . '/../logs/auth.log', Logger::INFO));

    if (!isset($conn)) {
        die("Database connection not established.");
    }

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $stmt = $conn->prepare("UPDATE users SET status = 0 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $log->info("User: $username | Status: logout | IP: {$_SERVER['REMOTE_ADDR']} | Time: " . date('Y-m-d H:i:s'));
    }

    session_destroy();
    header('Location: index.php');
    exit();
}
?>