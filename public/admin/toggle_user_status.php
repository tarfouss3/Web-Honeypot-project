<?php
//require_once '../../config/db.php';
//require_once '../../src/session.php';
//
//if (!isset($conn)) {
//    die("Database connection not established.");
//}
//if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
//    header('Location: ../login.php');
//    exit();
//}
//
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    $user_id = $_POST['user_id'];
//
//    // Check if the user is logged in
//    $query = "SELECT status FROM users WHERE id = ?";
//    $stmt = $conn->prepare($query);
//    $stmt->bind_param("i", $user_id);
//    $stmt->execute();
//    $stmt->bind_result($status);
//    $stmt->fetch();
//    $stmt->close();
//
//    // Toggle the user's status
//    $new_status = $status ? 0 : 1;
//
//    // Update the user's status in the database
//    $query = "UPDATE users SET status = ? WHERE id = ?";
//    $stmt = $conn->prepare($query);
//    $stmt->bind_param("ii", $new_status, $user_id);
//
//    if ($stmt->execute()) {
//        header('Location: dashboard.php');
//    } else {
//        echo "Error: " . $stmt->error;
//    }
//}
//?>