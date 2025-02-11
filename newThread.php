<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page.
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$title = '';
$content = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = 'Both title and content are required.';
    } else {
        // Retrieve current user from the session.
        $username = $_SESSION['username'];
        $date = date("Y-m-d H:i:s");
        // For demonstration, use the current timestamp as a unique thread ID.
        $id = time();

        // Open (or create) the CSV file and append the new thread.
        $file = fopen('threads.csv', 'a');
        fputcsv($file, array($id, $title, $username, $content, $date));
        fclose($file);

        // Redirect the user to the forum page after successful creation.
        header("Location: forum.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Thread</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Create New Thread</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label for="title">Thread Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div>
            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        <div>
            <button type="submit">Create Thread</button>
        </div>
    </form>
</body>
</html>
