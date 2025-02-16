<?php

class HeaderCopoment
{

    private $title;
    private $username;

    public function __construct($title)
    {
        // Start the session if it's not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->title = $title;

        $this->username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

    }


    public function render()
    {
        ?>
        <header class="site-header">
            <div class="header-container">
                <h1 class="site-title"><a href="index.php" class="title-a"
                                          style="font-family: Roboto, sans-serif"><?= htmlspecialchars($this->title) ?></a>
                </h1>
                <?php if (empty($this->username)): ?>
                    <a href="login.php" class="login-btn">Login</a>
                <?php else: ?>
                    <form id="logoutForm" method="post" action="./Functions/logout.php">
                        <button class="login-btn"><?= htmlspecialchars($this->username) ?></button>
                    </form>
                <?php endif; ?>

            </div>
        </header>

        <script>
            // Wait until the DOM is fully loaded
            document.addEventListener('DOMContentLoaded', function () {
                // Select the logout form using its ID
                var logoutForm = document.getElementById('logoutForm');

                if (logoutForm) {
                    logoutForm.addEventListener('submit', function (e) {
                        // Show a confirmation dialog when the user clicks the logout button
                        var confirmed = confirm("Are you sure you want to logout?");

                        // If the user clicks "Cancel", prevent the form from submitting
                        if (!confirmed) {
                            e.preventDefault();
                        }
                        // If confirmed, the form will submit and your PHP script will execute
                    });
                }
            });
        </script>


        <?php
    }
}

?>