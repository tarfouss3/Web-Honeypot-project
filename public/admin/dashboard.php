<?php
require_once '../../config/db.php';
require_once '../../src/session.php';

if (!isset($conn)) {
    die("Database connection not established.");
}
if (!isLoggedIn() || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'SuperUser')) {
    header('Location: ../login.php');
    exit();
}

$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Admin Dashboard</title>
    <style>
        /* Existing styles */
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #ffeb3b;
        }
        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2.5em;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown:hover .dropbtn {
            background-color: #555;
        }
        .dropbtn {
            background-color: #333;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        }
        .dropbtn:hover {
            background-color: #555;
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
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
    <?php if ($user_role === 'SuperUser'): ?>
        <div class="dropdown">
            <button class="dropbtn">Users</button>
            <div class="dropdown-content">
                <a href="view_logged_in_users.php">View Logged-in Users</a>
                <a href="customers.php">Customers</a>
        </div>
    <?php else: ?>
        <div class="dropdown">
            <button class="dropbtn">Manage User Roles</button>
            <div class="dropdown-content">
                <a href="update_role.php">Update Role</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Products</button>
            <div class="dropdown-content">
                <a href="add_product.php">Add New Product</a>
                <a href="update_product.php">Update Product</a>
                <a href="delete_product.php">Delete Product</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Users</button>
            <div class="dropdown-content">
                <a href="view_all_users.php">View All Users</a>
                <a href="view_logged_in_users.php">View Logged-in Users</a>
                <a href="enable_disable_user.php">Enable/Disable Users</a>
            </div>
        </div>
    <?php endif; ?>
</main>
</body>
</html>