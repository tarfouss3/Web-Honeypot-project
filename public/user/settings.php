<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
$log = require_once '../../logger.php';

if (!isset($conn)) {
    die("Database connection not established.");
}

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

$message = "";

$user_id = $_SESSION['user_id'];
header("X-User-Id: $user_id");

$query = "SELECT user_role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$stmt->close();

$protected_user_ids = [14, 4];
if ($user_role === 'SuperUser') {
    $message = " You are a SuperUser. changing username is not possible.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_SERVER['HTTP_X_USER_ID'])) {
        $target_user_id = $_SERVER['HTTP_X_USER_ID'];
    } else {
        $target_user_id = $_SESSION['user_id'];
    }

    $new_username = $_POST['username'];

    if ($target_user_id != $user_id) {
        $log->warning('Broken Access Control Attempt', [
            'Attacker-Account' => $_SESSION['user_id'],
            'Target' => $target_user_id,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'request_uri' => $_SERVER['REQUEST_URI'],
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'request_query' => $_SERVER['QUERY_STRING'],
        ]);
    }

    if (!in_array($target_user_id, $protected_user_ids)) {
        $update_query = "UPDATE users SET username = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_username, $target_user_id);
        $update_stmt->execute();
        $update_stmt->close();

        session_destroy();

        $message = "Username updated successfully! You will be logged out and redirected to the login page in a few seconds.";
        header("refresh:5;url=../login.php");
    } else {
        $message = "This user's username is not changeable.";
    }
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