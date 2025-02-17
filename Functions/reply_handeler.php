<?php
// Start a session if one hasn't been started yet.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only process the reply if the form is submitted via POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the current Unix timestamp to record when the reply was made.
    $time = time();

    // Retrieve the username from the session (trim to remove any extra whitespace).
    $username = isset($_SESSION['username']) ? trim($_SESSION['username']) : '';

    // Retrieve the thread file name from the submitted form data.
    $file_name = isset($_POST['thread_file']) ? $_POST['thread_file'] : '';

    // Retrieve the reply content from the submitted form data.
    $reply = isset($_POST['reply']) ? $_POST['reply'] : '';

    // Build an associative array containing the reply data.
    // Note: 'conetnt' appears to be a typo but is kept unchanged.
    $data = [
        'username' => $username,
        'time' => $time,
        'conetnt' => $reply
    ];

    // Convert the data array into a string with " | " as a delimiter and add a newline at the end.
    $line = implode(" | ", $data) . PHP_EOL;

    // Define the file path to the thread's CSV file where the reply will be appended.
    $file_path = '../Database/Threads/' . $file_name;

    // Append the reply line to the thread CSV file.
    file_put_contents($file_path, $line, FILE_APPEND);

    // Redirect the user back to the homepage after processing the reply.
    header("Location: ../index.php");
}
?>
