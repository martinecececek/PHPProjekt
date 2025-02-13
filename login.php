<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and trim form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Not used in this simple example, but available for further checks

    // Flag to indicate if the username was found
    $userFound = false;

    // Path to your CSV file (adjust the path if necessary)
    $csvFile = $_SESSION['users_path'];

    // Check if file exists
    if (file_exists($csvFile)) {
        if (($handle = fopen($csvFile, "r")) !== false) {
            // Loop through each row of the CSV file
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Assuming the CSV's first column is the username.
                if (isset($data[0]) && $data[0] === $username) {
                    $userFound = true;
                    // You can add password verification here if needed.
                    break; // Stop reading once we found a match.
                }
            }
            fclose($handle);
        } else {
            // Handle error opening the CSV file
            die("Unable to open user data file.");
        }
    } else {
        // Handle missing CSV file error
        die("User data file not found.");
    }

    // If the username exists in the CSV, redirect to index.php
    if ($userFound) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        // Prepare an error message to show on the form
        $errorMessage = "Invalid username. Please try again.";
    }
}

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
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

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
