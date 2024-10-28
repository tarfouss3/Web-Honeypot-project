<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];

    $query = "SELECT status FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    $new_status = $status ? 0 : 1;

    $query = "UPDATE users SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $new_status, $user_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>