<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the order
    // Clear the cart
    $_SESSION['cart'] = [];
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Checkout</title>
</head>
<body>
<header>
    <h1>Checkout</h1>
</header>
<main>
    <form method="POST">
        <label for="address">Shipping Address:</label>
        <input type="text" id="address" name="address" required><br>
        <label for="payment">Payment Method:</label>
        <input type="text" id="payment" name="payment" required><br>
        <button type="submit">Place Order</button>
        <p><a href="index.php" class="return-button">Return to Home</a></p>
    </form>
</main>
<?php include 'footer.php'; ?>
</body>
</html>