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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'enable') {
        $query = "UPDATE users SET is_active = 1 WHERE id = ?";
    } else {
        $query = "UPDATE users SET is_active = 0 WHERE id = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    header('Location: enable_disable_user.php');
}

$query = "SELECT id, username, is_active FROM users";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Enable/Disable Users</title>
    <style>
        #userEnable {
            background: none;
            padding: 0;
            margin: 0;
            box-shadow: none;
            max-width: none;
            border-radius: 0;
        }
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #ffeb3b;
        }
        main {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        }
        button:hover {
            background-color: #555;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<header>
    <h1>Enable/Disable Users</h1>
    <nav>
        <a href="dashboard.php">Back to Dashboard</a>
    </nav>
</header>
<main>
    <table>
        <tr>
            <th>Username</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['is_active'] ? 'Enabled' : 'Disabled'; ?></td>
                <td>
                    <form id="userEnable" method="post" action="enable_disable_user.php">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <?php if ($row['is_active']): ?>
                            <button type="submit" name="action" value="disable">Disable</button>
                        <?php else: ?>
                            <button type="submit" name="action" value="enable">Enable</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>