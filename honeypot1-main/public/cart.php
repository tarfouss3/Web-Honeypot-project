<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $_SESSION['cart'][] = $product_id;
}

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Cart</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        main {
            padding: 40px 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        li:last-child {
            border-bottom: none;
        }
        .product-name {
            font-weight: bold;
            color: #333;
        }
        .product-price {
            color: #007bff;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .actions a {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .actions a:last-child {
            margin-right: 0;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <h1>Your Cart</h1>
</header>
<main>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <span class="product-name"><?php echo htmlspecialchars($product['name']); ?></span>
                <span class="product-price">$<?php echo htmlspecialchars($product['price']); ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="actions">
        <a href="checkout.php">Proceed to Checkout</a>
        <a href="index.php">Return to Home</a>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>