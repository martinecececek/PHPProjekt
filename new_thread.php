<?php

require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

require_once 'Components/footerComponent.php';
$footer = new FooterComponent();

require_once 'Components/threadComponent.php';

?>


<?php

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $time = time();
    $username = isset($_SESSION['username']) ? trim($_SESSION['username']) : '';
    $title = isset($_POST['threadTitle']) ? trim($_POST['threadTitle']) : '';
    $content = isset($_POST['threadContent']) ? trim($_POST['threadContent']) : '';
    $theme = isset($_POST['threadCategory']) ? trim($_POST['threadCategory']) : '';

    // Build the data array
    $data = [
        'username' => $username,
        'title' => $title,
        'time' => $time,
        'content' => $content,
    ];

    // Create a string from the data array with " | " as the delimiter
    $line = implode(" | ", $data) . PHP_EOL;

    // Define the file path using a session variable and a combination of time and theme
    $file_path = './Database/Threads' . '/' . $time . $theme . '.csv';

    // Create (or overwrite) the file with the content
    file_put_contents($file_path, $line);

    $separator_line = "-------" . PHP_EOL;
    file_put_contents($file_path, $separator_line, FILE_APPEND);

    header("Location: index.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create New Thread</title>
    <link rel="stylesheet" href="./css/new_thread.css">
</head>
<body>
<!-- Site Header -->
<?php $header->render(); ?>

<!-- Thread Creation Form -->
<div class="login-container">
    <div class="login-header">Create New Thread</div>
    <form class="login-form" method="POST" action="new_thread.php">
        <label for="threadTitle">Thread Title</label>
        <input
            type="text"
            id="threadTitle"
            name="threadTitle"
            placeholder="Enter thread title"
            required
        />

        <label for="threadCategory">Category</label>
        <select id="threadCategory" name="threadCategory" required>
            <option value="">Select Category</option>
            <option value="_te">💻 Technology</option>
            <option value="_en">🎬 Entertainment</option>
            <option value="_tr">✈️ Travel</option>
            <option value="_ga">🌱 Gardening</option>
        </select>

        <label for="threadContent">Content</label>
        <textarea
            id="autoResize"
            name="threadContent"
            style="min-height:50px; resize: none; overflow: hidden;"
        ></textarea>

        <button type="submit" class="login-button">Create Thread</button>
    </form>
</div>


<script>
    const textarea = document.getElementById('autoResize');
    textarea.addEventListener('input', function () {
        // Reset the height to recalculate correctly when text is removed
        this.style.height = 'auto';
        // Set the height based on the scroll height (content height)
        this.style.height = this.scrollHeight + 'px';
    });
</script>

<!-- Site Footer -->
<?php $footer->render(); ?>

</body>
</html>
