<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isset($conn)) {
    die("Database connection not established.");
}
// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

$message = "";

// Set the X-User-Id header internally
$user_id = $_SESSION['user_id'];
header("X-User-Id: $user_id");

// Fetch user role
$query = "SELECT user_role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$stmt->close();

if ($user_role === 'fakeadmin') {
    $message = "You are not authorized to change your username.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'];

    // Update the username
    $update_query = "UPDATE users SET username = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_username, $user_id);
    $update_stmt->execute();
    $update_stmt->close();

    session_destroy();

    $message = "Username updated successfully! You will be logged out and redirected to the login page in a few seconds.";
    header("refresh:5;url=../login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>User Settings</title>
</head>
<body>
<header>
    <h1>User Settings</h1>
</header>
<main>
    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?php echo $message; ?></p>
    <?php else: ?>
        <form action="settings.php" method="POST">
            <label for="username">New Username:</label>
            <input type="text" name="username" id="username" required>
            <button type="submit">Update Username</button>
        </form>
    <?php endif; ?>
    <p><a href="profile.php" class="return-button">Return to Profile</a></p>
</main>
<?php include '../footer.php'; ?>
</body>
</html>