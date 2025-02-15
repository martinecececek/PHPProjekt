<?php

require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

require_once 'Components/footerComponent.php';
$footer = new FooterComponent();

require_once 'Components/threadComponent.php';

?>


<?php


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
    <form class="login-form">
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
            <option value="general">General</option>
            <option value="announcements">Announcements</option>
            <option value="feedback">Feedback</option>
        </select>

        <label for="threadContent">Content</label>
        <textarea
            id="autoResize"
            name="autoResize"
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
