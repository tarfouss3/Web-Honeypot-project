<?php
session_start();
require_once '../../config/db.php';
require_once '../../src/session.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}
if (!isset($conn)) {
    die("Database connection not established.");
}

$user_id = $_GET['id'];
$stmt = $conn->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

header('Location: dashboard.php');
exit();
?>