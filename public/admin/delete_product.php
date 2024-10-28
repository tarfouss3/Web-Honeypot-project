<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];

    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Delete Product</title>
</head>
<body>
<header>
    <h1>Delete Product</h1>
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>
</header>
<main>
    <form action="delete_product.php" method="post">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" required>
        <button type="submit">Delete Product</button>
    </form>
</main>
</body>
</html>