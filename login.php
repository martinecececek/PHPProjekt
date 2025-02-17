<?php
// Start a session if one hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the form has been submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize the username and password from the form input
    $username = isset($_POST['usrnm']) ? trim($_POST['usrnm']) : '';
    $password = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    // Path to the CSV file containing user credentials
    $csvFile = './Database/Users/users.csv';

    // Verify that the CSV file exists; if not, terminate with an error message
    if (!file_exists($csvFile)) {
        die("CSV file not found at: " . htmlspecialchars($csvFile));
    }

    // Flag to indicate whether a matching user was found
    $userFound = false;

    // Open the CSV file for reading
    if (($handle = fopen($csvFile, 'r')) !== false) {
        // Loop through each line (row) of the CSV file
        while (($data = fgetcsv($handle, 1000, "|")) !== false) {
            // Assuming the CSV format is: username|password
            // Check if both username and password exist in the current row
            if (isset($data[0], $data[1]) && trim($data[0]) === $username && trim($data[1]) === $password) {
                $userFound = true; // User credentials match
                break;             // Exit the loop as we found the user
            }
        }
        fclose($handle); // Close the CSV file after reading
    } else {
        // Terminate if the CSV file could not be opened
        die("Unable to open CSV file.");
    }

    // If a matching user was found, log them in by saving the username in the session
    if ($userFound) {
        $_SESSION['username'] = $username;
        header("Location: index.php"); // Redirect to the main page after login
        exit(); // Terminate the script immediately after redirection
    } else {
        // If the user was not found, display an error message
        echo "Invalid username or password.";
    }
}
?>

<?php
// Include header and footer component files after processing the POST data

// Include the header component and create an instance with the title "Discusion forum"
require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

// Include the footer component and create an instance
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

<!-- Render the header component -->
<?php $header->render(); ?>

<div class="wrapper">
    <div class="login-container">
        <div class="login-header">
            Login to Your Account
        </div>
        <?php
        // Display error message if one exists (e.g., invalid login)
        if (isset($errorMessage)) {
            echo '<p style="color:red;">' . htmlspecialchars($errorMessage) . '</p>';
        }
        ?>
        <!-- Login form for users to enter their credentials -->
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

<!-- Render the footer component -->
<?php $footer->render(); ?>
</body>
</html>
