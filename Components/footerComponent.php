<?php

class FooterComponent
{

    public function render()
    {
        ?>
        <footer class="site-footer">
            <div class="footer-container">
                <div class="footer-content">
                    <p>&copy; 2025 School Discussion Forum. All rights reserved.</p>
                    <p>
                        School Discussion Forum is a community-driven platform dedicated to fostering engaging
                        discussions among students, teachers, and staff. Join us to share ideas, learn, and grow
                        together.
                    </p>
                    <p>
                        <a href="terms.php" style="color: var(--light-color); margin-right: 10px;">Terms of Use</a>
                        <a href="privacy.php" style="color: var(--light-color); margin-right: 10px;">Privacy Policy</a>
                        <a href="contact.php" style="color: var(--light-color); margin-right: 10px;">Contact Us</a>
                        <a href="login.php" style="color: var(--light-color); margin-right: 10px;">Login</a>
                    </p>
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
