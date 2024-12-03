<?php
session_start();
require_once '../config/db.php';

$error_message = '';
if (!isset($conn)) {
    die("Database connection not established.");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required.";
    } elseif (preg_match('/\s/', $username)) {
        $error_message = "Username cannot contain spaces.";
    } elseif (!empty($_FILES['avatar']['tmp_name'])) {
        $avatar = $_FILES['avatar']['name'];
        $target_dir = '../assets/avatars/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMime = finfo_file($fileInfo, $_FILES['avatar']['tmp_name']);
        finfo_close($fileInfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($fileMime, $allowedTypes)) {
            $error_message = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
        } else {
            $imageFileType = mime_content_type($_FILES['avatar']['tmp_name']);

            if (!in_array($imageFileType, $allowedTypes)) {
                $error_message = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
            } else {
                $avatar = uniqid() . '-' . preg_replace("/[^a-zA-Z0-9\.\-_]/", "", basename($_FILES['avatar']['name']));
                $target = $target_dir . $avatar;

                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
                    $password_hashed = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("INSERT INTO users (username, password, avatar) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $password_hashed, $avatar);
                    $stmt->execute();
                    header('Location: login.php');
                } else {
                    $error_message = "Failed to upload avatar.";
                }
            }
        }
    } else {
        $error_message = "Avatar is required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Register</title>
</head>
<body>
<header>
    <h1>Register</h1>
</header>
<main>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" id="avatar" required>
        <button type="submit">Register</button>
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <p>Already have an account? <a href="login.php">Login</a></p>
        <p><a href="index.php" class="return-button">Return to Home</a></p>
    </form>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
