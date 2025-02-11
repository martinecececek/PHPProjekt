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
                  <h1 class="site-title"><?= htmlspecialchars($this->title) ?></h1>
                    <?php if (empty($this->username)): ?>
                        <button class="login-btn">Login</button>
                    <?php else: ?>
                        <button class="login-btn"><?= htmlspecialchars($this->username) ?></button>
                    <?php endif; ?>
                </div>
              </header>
        <?php
    }

}
?>