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
    $message = " You are a SuperUser. Changing username is not possible.";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_SERVER['HTTP_X_USER_ID'])) {
        $target_user_id = $_SERVER['HTTP_X_USER_ID'];
    } else {
        $target_user_id = $_SESSION['user_id'];
    }

    $new_username = trim($_POST['username']);

    // Validation for the new username
    if (empty($new_username)) {
        $message = "Username cannot be empty.";
    } elseif (preg_match('/\s/', $new_username)) {
        $message = "Username cannot contain spaces.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username) || preg_match('/[\'";\\]/', $new_username)) {
        $message = "Invalid username format. Only alphanumeric characters and underscores are allowed.";
    } else {
        // Log any attempts to target other users
        if ($target_user_id != $user_id) {
            $log->warning(
                "Attack-Type: brokenAccessControl | Attacker-Account: {$_SESSION['user_id']} | Target: $target_user_id | IP: {$_SERVER['REMOTE_ADDR']} | User-Agent: {$_SERVER['HTTP_USER_AGENT']} | Request-URI: {$_SERVER['REQUEST_URI']} | Request-Method: {$_SERVER['REQUEST_METHOD']} | Request-Query: {$_SERVER['QUERY_STRING']}"
            );
        }

        // Check if username already exists
        $check_query = "SELECT id FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $new_username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Username already exists.";
        } else {
            // Proceed with updating username
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
        $check_stmt->close();
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
        <p style="color: green; font-weight: bold;">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>
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
