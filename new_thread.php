<?php
// Include the header component file and create an instance with the forum title.
require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

// Include the footer component file and create its instance.
require_once 'Components/footerComponent.php';
$footer = new FooterComponent();

// Include the thread component file (likely used elsewhere in the application).
require_once 'Components/threadComponent.php';
?>

<?php
// Initialize an empty array to hold any potential error messages.
$errors = [];

// Check if the form has been submitted via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the current time to use as a unique identifier for the thread.
    $time = time();

    // Retrieve the username from the session if set (trim any whitespace).
    $username = isset($_SESSION['username']) ? trim($_SESSION['username']) : '';

    // Retrieve and trim the thread title from the submitted form.
    $title = isset($_POST['threadTitle']) ? trim($_POST['threadTitle']) : '';

    // Retrieve and trim the thread content from the submitted form.
    $content = isset($_POST['threadContent']) ? trim($_POST['threadContent']) : '';

    // Retrieve and trim the selected category (theme) for the thread.
    $theme = isset($_POST['threadCategory']) ? trim($_POST['threadCategory']) : '';

    // Build an associative array containing the thread data.
    $data = [
        'username' => $username,
        'title' => $title,
        'time' => $time,
        'content' => $content,
    ];

    // Convert the data array into a string separated by " | " and add a newline at the end.
    $line = implode(" | ", $data) . PHP_EOL;

    // Define the file path for the new thread CSV file.
    // The filename is built using the current time and the selected theme.
    $file_path = './Database/Threads' . '/' . $time . $theme . '.csv';

    // Create or overwrite the CSV file with the thread data.
    file_put_contents($file_path, $line);

    // Define a separator line (could be used to separate the initial post from replies).
    $separator_line = "-------" . PHP_EOL;
    // Append the separator line to the CSV file.
    file_put_contents($file_path, $separator_line, FILE_APPEND);

    // After creating the thread, redirect the user to the index page.
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create New Thread</title>
    <!-- Link to the CSS file for styling the new thread page -->
    <link rel="stylesheet" href="./css/new_thread.css">
</head>
<body>
<!-- Render the site header using the header component -->
<?php $header->render(); ?>

<!-- Thread Creation Form Container -->
<div class="login-container">
    <div class="login-header">Create New Thread</div>
    <!-- Form for creating a new thread; submits to the same page (new_thread.php) -->
    <form class="login-form" method="POST" action="new_thread.php">
        <!-- Input for the thread title -->
        <label for="threadTitle">Thread Title</label>
        <input
            type="text"
            id="threadTitle"
            name="threadTitle"
            placeholder="Enter thread title"
            required
        />

        <!-- Dropdown selection for the thread category -->
        <label for="threadCategory">Category</label>
        <select id="threadCategory" name="threadCategory" required>
            <option value="">Select Category</option>
            <option value="_te">💻 Technology</option>
            <option value="_en">🎬 Entertainment</option>
            <option value="_tr">✈️ Travel</option>
            <option value="_ga">🌱 Gardening</option>
        </select>

        <!-- Textarea for the thread content -->
        <label for="threadContent">Content</label>
        <textarea
            id="autoResize"
            name="threadContent"
            style="min-height:50px; resize: none; overflow: hidden;"
        ></textarea>

        <!-- Submit button to create the new thread -->
        <button type="submit" class="login-button">Create Thread</button>
    </form>
</div>

<!-- JavaScript to auto-resize the textarea as text is entered -->
<script src="./JS/bigget_textarea.js"></script>

<!-- Render the site footer using the footer component -->
<?php $footer->render(); ?>

</body>
</html>
