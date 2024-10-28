<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isset($conn)) {
    die("Database connection not established.");
}
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Admin Dashboard</title>
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="../index.php">Home</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>
<main>
    <h2>Admin Actions</h2>
    <ul>
        <li><a href="update_role.php">Manage User Roles</a></li>
        <li><a href="add_product.php">Add New Product</a></li>
        <li><a href="update_product.php">Update Product</a></li>
        <li><a href="delete_product.php">Delete Product</a></li>
        <li><a href="view_all_users.php">View All Users</a></li>
        <li><a href="view_logged_in_users.php">View Logged-in Users</a></li>
    </ul>
</main>
</body>
</html>