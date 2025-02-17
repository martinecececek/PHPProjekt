<?php

// The HeaderCopoment class is responsible for rendering the website's header.
class HeaderCopoment
{
    // Private properties to store the title and the username of the logged-in user.
    private $title;
    private $username;

    // Constructor that initializes the title and retrieves the username from the session.
    public function __construct($title)
    {
        // Start the session if it hasn't been started already.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Set the title for the header.
        $this->title = $title;

        // Retrieve the username from the session if available; otherwise, use an empty string.
        $this->username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
    }

    // Renders the header HTML along with a logout form or login link depending on the user's login status.
    public function render()
    {
        ?>
        <header class="site-header">
            <div class="header-container">
                <!-- Website title that links to the homepage -->
                <h1 class="site-title">
                    <a href="index.php" class="title-a" style="font-family: Roboto, sans-serif">
                        <?= htmlspecialchars($this->title) ?>
                    </a>
                </h1>
                <?php if (empty($this->username)): ?>
                    <!-- If no user is logged in, display a login button that redirects to the login page -->
                    <a href="login.php" class="login-btn">Login</a>
                <?php else: ?>
                    <!-- If a user is logged in, display a logout form with the username as the button label -->
                    <form id="logoutForm" method="post" action="./Functions/logout.php">
                        <button class="login-btn"><?= htmlspecialchars($this->username) ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </header>

        <!-- JavaScript to add a confirmation dialog when logging out -->
        <script src="./JS/logout.js"></script>
        <?php
    }
}

?>
