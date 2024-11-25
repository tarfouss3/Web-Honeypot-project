<?php
require_once '../../config/db.php';
require_once '../../src/session.php';
$log = require_once '../../logger.php';

header('X-Is-Admin: false');

$isAdmin = false;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT user_role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['user_role'] === 'admin' || $row['user_role'] === 'SuperUser') {
            $isAdmin = true;
        }
    }
    $stmt->close();
}

if (isset($_SERVER['X-Is-Admin']) && $_SERVER['X-Is-Admin'] === 'true') {
    $isAdmin = true;
}

if (!$isAdmin) {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'unknown';
    $log->warning(
        "Attack-Type: UnauthorizedAccessFailed | Attacker-Account: $username | IP: {$_SERVER['REMOTE_ADDR']} | User-Agent: {$_SERVER['HTTP_USER_AGENT']} | Request-URI: {$_SERVER['REQUEST_URI']} | Request-Method: {$_SERVER['REQUEST_METHOD']} | Request-Query: {$_SERVER['QUERY_STRING']}"
    );
    die("Access denied.");
}

// Log successful access by a hacker
$log->warning(
    "Attack-Type: UnauthorizedAccessSuccess | Attacker-Account: {$_SESSION['username']} | IP: {$_SERVER['REMOTE_ADDR']} | User-Agent: {$_SERVER['HTTP_USER_AGENT']} | Request-URI: {$_SERVER['REQUEST_URI']} | Request-Method: {$_SERVER['REQUEST_METHOD']} | Request-Query: {$_SERVER['QUERY_STRING']}"
);

$fakeCustomers = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@gmail.com'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane.smith@yahoo.com'],
    ['id' => 3, 'name' => 'Alice Johnson', 'email' => 'alice.johnson@outlook.com'],
    ['id' => 4, 'name' => 'Bob Brown', 'email' => 'bob.brown@hotmail.com'],
    ['id' => 5, 'name' => 'Charlie Davis', 'email' => 'charlie.davis@gmail.com'],
    ['id' => 6, 'name' => 'Emily White', 'email' => 'emily.white@yahoo.com'],
    ['id' => 7, 'name' => 'Frank Harris', 'email' => 'frank.harris@gmail.com'],
    ['id' => 8, 'name' => 'Grace Clark', 'email' => 'grace.clark@icloud.com'],
    ['id' => 9, 'name' => 'Henry Adams', 'email' => 'henry.adams@hotmail.com'],
    ['id' => 10, 'name' => 'Isabella Carter', 'email' => 'isabella.carter@outlook.com'],
    ['id' => 11, 'name' => 'Jack Thomas', 'email' => 'jack.thomas@gmail.com'],
    ['id' => 12, 'name' => 'Karen Walker', 'email' => 'karen.walker@yahoo.com'],
    ['id' => 13, 'name' => 'Leo Baker', 'email' => 'leo.baker@icloud.com'],
    ['id' => 14, 'name' => 'Mia King', 'email' => 'mia.king@outlook.com'],
    ['id' => 15, 'name' => 'Nathan Green', 'email' => 'nathan.green@gmail.com'],
    ['id' => 16, 'name' => 'Olivia Hall', 'email' => 'olivia.hall@hotmail.com'],
    ['id' => 17, 'name' => 'Paul Lee', 'email' => 'paul.lee@yahoo.com'],
    ['id' => 18, 'name' => 'Quinn Perez', 'email' => 'quinn.perez@gmail.com'],
    ['id' => 19, 'name' => 'Ryan Scott', 'email' => 'ryan.scott@outlook.com'],
    ['id' => 20, 'name' => 'Sophia Hill', 'email' => 'sophia.hill@icloud.com'],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Database</title>
    <link href="../../assets/styles.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
<header>
    <h1>Customer Database</h1>
    <nav>
        <a href="../index.php">Back to main</a>
    </nav>
</header>
<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fakeCustomers as $customer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['id']); ?></td>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
