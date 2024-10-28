<?php
require_once '../config/db.php';
require_once '../src/session.php';

if (!isset($conn)) {
    die("Database connection not established.");
}

$query = "SELECT * FROM products";
$result = $conn->query($query);
$user_role = null;
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT user_role FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_role);
    $stmt->fetch();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>iPhone Dealer</title>
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
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: calc(33.333% - 40px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .product-item img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            border-radius: 10px 10px 0 0;
        }
        .product-item h3 {
            margin: 15px 0;
            font-size: 1.5em;
            color: #333;
        }
        .product-item p {
            margin: 10px 0;
            color: #666;
        }
        .product-item a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .product-item a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <h1>Welcome to the iPhone Dealer</h1>
    <nav>
        <?php if (isLoggedIn()): ?>
            <a href="user/profile.php">Profile</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
            <?php if ($user_role === 'admin'): ?>
                <a href="admin/dashboard.php">Admin Panel</a>
            <?php elseif ($user_role === 'fakeadmin'): ?>
                <a href="admin/view_logged_in_users.php">View Logged-in Users</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="cart.php">Cart</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <h2>Available iPhones</h2>
    <div class="product-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-item">
                <img src="../assets/product_images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p>$<?php echo $row['price']; ?></p>
                <a href="product.php?id=<?php echo $row['id']; ?>">View Details</a>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>