<?php
// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear the username from the session to effectively log out the user
$_SESSION['username'] = '';

// Redirect the user to the homepage (index.php) located in the parent directory
header("Location: ../index.php");
?>
