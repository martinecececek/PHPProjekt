<?php

// FooterComponent class is responsible for rendering the footer section of the website.
class FooterComponent
{
    // Renders the footer HTML.
    public function render()
    {
        ?>
        <footer class="site-footer">
            <div class="footer-container">
                <div class="footer-content">
                    <!-- Display copyright information -->
                    <p>&copy; 2025 School Discussion Forum. All rights reserved.</p>
                    <!-- Provide a brief description about the platform -->
                    <p>
                        School Discussion Forum is a community-driven platform dedicated to fostering engaging
                        discussions among students, teachers, and staff. Join us to share ideas, learn, and grow
                        together.
                    </p>
                    <!-- Links to various policy pages and login page -->
                    <p>
                        <a href="terms.php" style="color: var(--light-color); margin-right: 10px;">Terms of Use</a>
                        <a href="privacy.php" style="color: var(--light-color); margin-right: 10px;">Privacy Policy</a>
                        <a href="contact.php" style="color: var(--light-color); margin-right: 10px;">Contact Us</a>
                        <a href="login.php" style="color: var(--light-color); margin-right: 10px;">Login</a>
                    </p>
                    <!-- Social media links for following the platform -->
                    <p>
                        Follow us on:
                        <a href="https://facebook.com" target="_blank"
                           style="color: var(--light-color); margin-right: 5px;">Facebook</a>
                        <a href="https://twitter.com" target="_blank"
                           style="color: var(--light-color); margin-right: 5px;">Twitter</a>
                        <a href="https://instagram.com" target="_blank"
                           style="color: var(--light-color); margin-right: 5px;">Instagram</a>
                    </p>
                </div>
            </div>
        </footer>
        <?php
    }
}

?>
