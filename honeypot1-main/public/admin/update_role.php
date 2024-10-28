<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
if (!isset($conn)) {
    die("Database connection not established.");
}
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$query = "SELECT id, username, user_role FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Manage User Roles</title>
</head>
<body>
<header>
    <h1>Manage User Roles</h1>
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>
</header>
<main>
    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['user_role']; ?></td>
                <td>
                    <form action="update_role.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <select name="new_role" required>
                            <option value="admin" <?php if ($row['user_role'] == 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="user" <?php if ($row['user_role'] == 'user') echo 'selected'; ?>>User</option>
                        </select>
                        <button type="submit">Update Role</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>