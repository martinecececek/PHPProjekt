<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $file_name = isset($_POST['thread_file']);
    $reply = isset($_POST['reply']);


    header("Location: index.php");
}
?>