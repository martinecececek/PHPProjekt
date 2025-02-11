<?php
session_start();

$_SESSION['username'] = '';
$_SESSION['thread_path'] = './Database/Threads'; // path to csv thread dir
$_SESSION['users_path'] = './Database/Users'; // path to csv users dir
$_SEESION['file_names'] = ['']; // array of all thread files

require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

require_once 'Components/footerComponent.php';
$footer = new FooterComponent();

require_once 'Components/threadComponent.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport">
    <title>Discussion Forum with Sticky Footer</title>
    <link rel="stylesheet" href="index.css" />
    

</head>

<body>

    <!-- Header -->
    <?php $header->render(); ?>

<div class="wrapper">


        <div class="forum">

            <!-- Get all filenames -->
            <?php
                $threadFiles = glob($_SESSION ['thread_path'] . '/*.csv');

                if ($threadFiles === false || count($threadFiles) === 0) {
                    echo "No threads found.";
                } else {
                    // Loop through each CSV file.
                    foreach ($threadFiles as $filePath) {
                        // Extract just the filename (without the directory path).
                        $fileName = basename($filePath);

                        // Create an instance of ThreadComponent with the filename.
                        $thread = new ThreadComponent($fileName);

                        // Render the thread (which will internally parse the CSV file).
                        $thread->render();
                    }
                }
            ?>

        </div>
    </div>

    <!-- Footer -->
    <?php $footer->render(); ?>

</body>
</html>
