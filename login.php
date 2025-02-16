<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input from the form
    $username = isset($_POST['usrnm']) ? trim($_POST['usrnm']) : '';
    $password = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    $csvFile = './Database/Users/users.csv';

    // Check if the CSV file exists
    if (!file_exists($csvFile)) {
        die("CSV file not found at: " . htmlspecialchars($csvFile));
    }

    // Flag to indicate a successful match
    $userFound = false;

    // Open the CSV file for reading
    if (($handle = fopen($csvFile, 'r')) !== false) {
        // Loop through each row in the CSV
        while (($data = fgetcsv($handle, 1000, "|")) !== false) {
            // Assuming CSV format: username, password
            if (isset($data[0], $data[1]) && trim($data[0]) === $username && trim($data[1]) === $password) {
                $userFound = true;
                break;
            }
        }
        fclose($handle);
    } else {
        die("Unable to open CSV file.");
    }

    if ($userFound) {
        // Store the username in the session and redirect
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit(); // Terminate script after redirect
    } else {
        // If credentials do not match, show an error message
        echo "Invalid username or password.";
    }
}
?>

<?php

// Include components after processing POST (if needed)
require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

require_once 'Components/footerComponent.php';
$footer = new FooterComponent();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Forum</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>

<!-- Header -->
<?php $header->render(); ?>

<div class="wrapper">
    <div class="login-container">
        <div class="login-header">
            Login to Your Account
        </div>
        <?php
        // If there's an error, display it here
        if (isset($errorMessage)) {
            echo '<p style="color:red;">' . htmlspecialchars($errorMessage) . '</p>';
        }
        ?>
        <form action="login.php" method="POST" class="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="usrnm" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="pass" placeholder="Enter your password" required>

            <button type="submit" class="login-button">Login</button>
        </form>
        <div class="login-footer">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>
</div>

<!-- Footer -->
<?php $footer->render(); ?>
</body>
</html>
