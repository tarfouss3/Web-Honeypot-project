<?php
session_start();

require_once '../config/db.php';

if (!isset($conn)) {
    die("Database connection not established.");
}

$order_placed = false;
$cart_items = $_SESSION['cart'] ?? [];
$products = [];

if (!empty($cart_items)) {
    $placeholders = implode(',', array_fill(0, count($cart_items), '?'));
    $query = "SELECT * FROM products WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('i', count($cart_items)), ...$cart_items);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $user_id = $_SESSION['user_id'];

    // Insert order details into the database
    $query = "INSERT INTO orders (user_id, status, created_at, address, payment_method) VALUES (?, 'pending', NOW(), ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $address, $payment);
    $stmt->execute();
    $stmt->close();

    // Clear the cart
    $_SESSION['cart'] = [];
    $order_placed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Checkout</title>
    <script>
        function redirectToHome() {
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 5000); // Redirect after 5 seconds
        }
    </script>
</head>
<body onload="redirectToHome()">
<header>
    <h1>Checkout</h1>
</header>
<main>
    <?php if ($order_placed): ?>
        <p>Your order has been placed and will be processed shortly.</p>
        <h2>Order Summary</h2>
        <ul>
            <?php foreach ($products as $product): ?>
                <li><?php echo htmlspecialchars($product['name']); ?> $<?php echo htmlspecialchars($product['price']); ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="index.php" class="return-button">Return to Home</a></p>
    <?php else: ?>
        <form method="POST">
            <label for="address">Shipping Address:</label>
            <input type="text" id="address" name="address" required><br>
            <label for="payment">Payment Method:</label>
            <select id="payment" name="payment" required>
                <option value="Pay in Cash">Pay in Cash</option>
                <option value="Pay Later">Pay Later</option>
            </select><br>
            <button type="submit">Place Order</button>
            <p><a href="index.php" class="return-button">Return to Home</a></p>
        </form>
    <?php endif; ?>
</main>
<?php include 'footer.php'; ?>
</body>
</html>