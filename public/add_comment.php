<?php
require_once '../config/db.php';
require_once '../src/session.php';
$log = require_once '../logger.php';

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

    if (preg_match('/<script.*?>.*?<\/script>/is', $content)) {
        $log->warning(
            "Attack-Type: XSS | Attacker-Account: {$_SESSION['username']} | Payload: $content | IP: {$_SERVER['REMOTE_ADDR']} "
        );
        $_SESSION['xss_detected'] = true;
        header('Location: product.php?id=' . $product_id);
        exit();
    }

    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO comments (product_id, username, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $product_id, $username, $content);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: product.php?id=' . $product_id);
exit();
?>
