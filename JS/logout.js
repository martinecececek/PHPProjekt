// Wait until the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function () {
    // Select the logout form using its ID
    var logoutForm = document.getElementById('logoutForm');

    if (logoutForm) {
        // Add an event listener to the logout form submission
        logoutForm.addEventListener('submit', function (e) {
            // Show a confirmation dialog when the user clicks the logout button
            var confirmed = confirm("Are you sure you want to logout?");

            // If the user clicks "Cancel", prevent the form from submitting
            if (!confirmed) {
                e.preventDefault();
            }
            // If confirmed, the form will submit and the logout process will continue
        });
    }
});