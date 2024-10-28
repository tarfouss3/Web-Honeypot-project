<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isset($conn)) {
    die("Database connection not established.");
}
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

$message = "";

// Fetch user role
$user_id = $_SESSION['user_id'];
$query = "SELECT user_role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$stmt->close();

if ($user_role === 'fakeadmin') {
    $message = "You are not authorized to change your password.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

        // Update the password
        $update_query = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        $update_stmt->execute();
        $update_stmt->close();

        session_destroy();

        $message = "Password updated successfully! You will be logged out and redirected to the login page in a few seconds.";
        header("refresh:5;url=../login.php");
    } else {
        $message = "Passwords do not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Change Password</title>

</head>
<body>
<header>
    <h1>Change Password</h1>
</header>
<main>
    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?php echo $message; ?></p>
    <?php else: ?>
        <form action="change_password.php" method="POST">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit">Change Password</button>
        </form>
    <?php endif; ?>
    <p><a href="profile.php" class="return-button">Return to Profile</a></p>
</main>
<?php include '../footer.php'; ?>
</body>
</html>