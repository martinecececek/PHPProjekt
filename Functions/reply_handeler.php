<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $time = time();
    $username = isset($_SESSION['username']) ? trim($_SESSION['username']) : '';
    $file_name = isset($_POST['thread_file']) ? $_POST['thread_file'] : '';
    $reply = isset($_POST['reply']) ? $_POST['reply'] : '';

    $data = [
        'username' => $username,
        'time' => $time,
        'conetnt' => $reply
    ];

    $line = implode(" | ", $data) . PHP_EOL;

    $file_path = '../Database/Threads/' . $file_name;

    file_put_contents($file_path, $line, FILE_APPEND);

    header("Location: ../index.php");
}
?>