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

$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
$content = $_SERVER['QUERY_STRING'];
if ($user_id !== $_SESSION['user_id']) {
    $log->warning(
    "Attack-Type: SQLi | Attacker-Account: {$_SESSION['user_id']} | Target: $user_id | Payload: $content | IP: {$_SERVER['REMOTE_ADDR']} | User-Agent: {$_SERVER['HTTP_USER_AGENT']} | Request-URI: {$_SERVER['REQUEST_URI']} | Request-Method: {$_SERVER['REQUEST_METHOD']} | Request-Query: {$_SERVER['QUERY_STRING']}"
);

}
$stmt = $conn->prepare("SELECT username, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        main {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .profile-avatar {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-avatar img {
            border-radius: 50%;
            border: 5px solid #333;
        }
        .profile-info {
            text-align: center;
        }
        .profile-info p {
            font-size: 18px;
            margin: 10px 0;
        }
        .profile-info a {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .profile-info a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<header>
    <h1>User Profile</h1>
    <!-- We can search other users with ?id= don't forget to remove this after we finish development !-->
</header>
<main>
    <p class="profile-avatar">
        <img src="../../assets/avatars/<?php echo htmlspecialchars($row['avatar']); ?>" alt="User Avatar" width="150" height="150">
        <?php if ($user_id == 4): ?>
            <!-- I identify as a JPG image; first, you must repair me to truly see who I am. -->
        <?php endif; ?>
    </p>
    <div class="profile-info">
        <p>Username: <?php echo htmlspecialchars($row['username']); ?></p>
        <p><a href="settings.php">Change Your Username</a></p>
        <p><a href="change_password.php">Change Your Password</a></p>
        <p><a href="../index.php">Return to Home</a></p>
    </div>
</main>
<?php include '../footer.php'; ?>
</body>
</html>
