<?php
require_once '../config/db.php';
require_once '../src/session.php';

if (!isset($conn)) {
    die("Database connection not established.");
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();
$stmt->close();

$comments_stmt = $conn->prepare("SELECT * FROM comments WHERE product_id = ? ORDER BY created_at DESC");
$comments_stmt->bind_param("i", $product_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comments_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
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
        .product-details {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.product-details img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

.product-details div {
    max-width: 600px; /* Ensure the text content has a max width */
    text-align: left; /* Align text to the left */
}

.product-details h2 {
    font-size: 2em;
    color: #333;
}

.product-details p {
    color: #666;
    margin: 10px 0; /* Add margin for better spacing */
}

.product-details form {
    margin-top: 20px;
}

.product-details button {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.product-details button:hover {
    background-color: #0056b3;
}
        .comments-section {
            margin-top: 40px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .comments-section h3 {
            font-size: 1.5em;
            color: #333;
        }
        .comment {
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .comment p {
            margin: 5px 0;
            color: #666;
        }
        .comment strong {
            color: #333;
        }
        .comments-section form {
            margin-top: 20px;
        }
        .comments-section textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }
        .comments-section button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .comments-section button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="user/profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
        <a href="index.php">Home</a>
        <a href="cart.php">Cart</a>
    </nav>
</header>
<main>
    <div class="product-details">
        <img src="../assets/product_images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <div>
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>$<?php echo htmlspecialchars($product['price']); ?></p>
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="comments-section">
        <h3>Comments</h3>
        <?php if (isset($_SESSION['xss_detected']) && $_SESSION['xss_detected']): ?>
            <script>alert("1");</script>
            <?php unset($_SESSION['xss_detected']); ?>
        <?php endif; ?>

        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo $comment['content']; ?></p>
                <p><small><?php echo htmlspecialchars($comment['created_at']); ?></small></p>
            </div>
        <?php endwhile; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="add_comment.php">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <textarea name="content" required></textarea><br>
                <button type="submit">Add Comment</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Login</a> to add a comment.</p>
        <?php endif; ?>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>