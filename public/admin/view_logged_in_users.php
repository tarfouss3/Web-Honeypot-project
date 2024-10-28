<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$query = "SELECT id, username, user_role FROM users WHERE status = 1";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>View Logged-in Users</title>
</head>
<body>
<header>
    <h1>View Logged-in Users</h1>
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>
</header>
<main>
    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['user_role']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>