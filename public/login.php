<?php
session_start();
require_once '../config/db.php';
require_once '../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('auth');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/auth.log', Logger::INFO));

if (!isset($conn)) {
    die("Database connection not established.");
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result === false) {
        die("Get result failed: " . $stmt->error);
    }

    $user = $result->fetch_assoc();
    if ($user) {
        if ($user['is_active'] == 0) {
            $error_message = "Your account is disabled.";
            $log->info("User: $username | Status: failure (account disabled) | IP: {$_SERVER['REMOTE_ADDR']} | Time: " . date('Y-m-d H:i:s'));
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];
            $stmt = $conn->prepare("UPDATE users SET status = 1 WHERE id = ?");
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();
            $stmt->close();
            $log->info("User: $username | Status: success | IP: {$_SERVER['REMOTE_ADDR']} | Time: " . date('Y-m-d H:i:s'));
            header('Location: index.php');
            exit();
        } else {
            $error_message = "Invalid username or password.";
            $log->info("User: $username | Status: failure (invalid password) | IP: {$_SERVER['REMOTE_ADDR']} | Time: " . date('Y-m-d H:i:s'));
        }
    } else {
        $error_message = "Invalid username or password.";
        $log->info("User: $username | Status: failure (invalid username) | IP: {$_SERVER['REMOTE_ADDR']} | Time: " . date('Y-m-d H:i:s'));
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Login</title>
</head>
<body>
<header>
    <h1>Login</h1>
</header>
<main>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Login</button>
        <?php if ($error_message): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <p><a href="index.php" class="return-button">Return to Home</a></p>
    </form>
</main>
<?php include 'footer.php'; ?>
</body>
</html>