<?php
session_start();
require_once '../config/db.php';

$error_message = '';
if (!isset($conn)) {
    die("Database connection not established.");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $avatar = $_FILES['avatar']['name'];
    $target = '../assets/' . basename($avatar);
    $imageFileType = mime_content_type($_FILES['avatar']['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO users (username, password, avatar) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $avatar);
            $stmt->execute();
            header('Location: login.php');
        } else {
            $error_message = "Failed to upload avatar.";
        }
    } else {
        $error_message = "Please upload a valid image file (JPEG, PNG, GIF).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Register</title>
</head>
<body>
<header>
    <h1>Register</h1>
</header>
<main>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Register</button>
    </form>
    <p><a href="index.php">Return to Home</a></p>
</main>
<?php include 'footer.php'; ?>
</body>
</html>