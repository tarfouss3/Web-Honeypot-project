<?php
session_start();
require_once '../config/db.php';
require_once '../src/session.php';

if (!isset($conn)) {
    die("Database connection not established.");
}

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $content = trim($_POST['content']);
    $username = $_SESSION['username'];

    $allowed_xss_payload = '<script>alert("1");</script>';

    if (!empty($content)) {
        if ($content === $allowed_xss_payload) {
            $_SESSION['xss_detected'] = true;
        } else {

            $sanitized_content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
            $stmt = $conn->prepare("INSERT INTO comments (product_id, username, content, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iss", $product_id, $username, $sanitized_content);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header('Location: product.php?id=' . $product_id);
exit();
?>