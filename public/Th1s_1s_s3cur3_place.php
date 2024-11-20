<?php
$log = require_once '../logger.php';

$log->warning('The hidden Volt has been accessed!!', [
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'request_method' => $_SERVER['REQUEST_METHOD'],
    'request_query' => $_SERVER['QUERY_STRING'],
]);

$secret = 78;
$entered_key = isset($_GET['key']) ? intval($_GET['key']) : null;
$message = "That is wrong! Get outta here!";

if ($entered_key === $secret) {
    $message = "Welcome admin !, The volt is unlocked here is your encrypted password! : eTJSUEo0UWFQRiFC";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hidden Directory</title>
    <link href="../assets/styles.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="main">
        <h2>Welcome to the Vault!</h2>
        <p>This is where I keep the admin password because it's too complex to remember. The good thing am good at math and hiding stuff</p>
        <!-- Solve this: (10 * 10) - (5 * 2) + (20 / 2) and then try to guess from 0 to that number to remember it -->
        <p>Enter the secret key to access the vault:</p>
        <p>key Entered: <?php echo htmlspecialchars($entered_key ?? ''); ?></p>
        <!-- ?key= -->
        <p><?php echo $message; ?></p>
    </div>
</body>
</html>