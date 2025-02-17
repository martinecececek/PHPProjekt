<?php
// Start the session if it hasn't been started yet.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the username session variable exists (its value should be set during login).
$_SESSION['username'];

// Set session variables for various paths and default values.
// Path to the directory containing thread CSV files.
$_SESSION['thread_path'] = './Database/Threads';
// Path to the CSV file containing user data.
$_SESSION['users_path'] = './Database/Users/users.csv';
// Initialize an array to hold thread file names (currently empty).
$_SESSION['file_names'] = [''];
// Default filter value; will be updated based on user input.
$_SESSION['filter'] = '';


// Include the header component file and create an instance with the title.
require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

// Include the footer component file and create its instance.
require_once 'Components/footerComponent.php';
$footer = new FooterComponent();

// Include the thread component which is used to render individual threads.
require_once 'Components/threadComponent.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Martineckovo forum</title>
    <!-- Link to the external CSS stylesheet with a version parameter to prevent caching issues -->
    <link rel="stylesheet" href="./css/index.css?v=2"/>
</head>

<!-- Include JavaScript files for UI effects -->
<script src="./JS/smooth_opening.js"></script>
<script src="./JS/render_more_replyes.js"></script>

<body>

<!-- Render the header section using the header component -->
<?php $header->render(); ?>

<div class="wrapper">
    <!-- Theme Filter Buttons Form -->
    <form method="post" action="" class="theme-filters">
        <!-- "All" filter button -->
        <button type="submit" name="filter" value="all"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'all') ? 'active' : ''; ?>">🔍 All
        </button>
        <!-- "Technology" filter button; active if filter code is 'te' -->
        <button type="submit" name="filter" value="technology"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'te') ? 'active' : ''; ?>">💻 Technology
        </button>
        <!-- "Entertainment" filter button; active if filter code is 'en' -->
        <button type="submit" name="filter" value="entertainment"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'en') ? 'active' : ''; ?>">🎬
            Entertainment
        </button>
        <!-- "Travel" filter button; active if filter code is 'tr' -->
        <button type="submit" name="filter" value="travel"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'tr') ? 'active' : ''; ?>">✈️ Travel
        </button>
        <!-- "Gardening" filter button; active if filter code is 'ga' -->
        <button type="submit" name="filter" value="gardening"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'ga') ? 'active' : ''; ?>">🌱 Gardening
        </button>
    </form>

    <!-- Link to the page for creating a new thread -->
    <div class="create-thread-container" style="text-align: center; margin-bottom: 20px;">
        <a href="new_thread.php" class="send-reply-btn">Create New Thread</a>
    </div>

    <div class="forum">
        <!-- PHP block to process the selected filter and display corresponding threads -->
        <?php

        // Update the session filter variable if a filter button has been submitted.
        if (isset($_POST['filter'])) {
            // Map the submitted filter value to a short code used in thread file naming.
            switch ($_POST['filter']) {
                case 'technology':
                    $_SESSION['filter'] = 'te';
                    break;
                case 'entertainment':
                    $_SESSION['filter'] = 'en';
                    break;
                case 'travel':
                    $_SESSION['filter'] = 'tr';
                    break;
                case 'gardening':
                    $_SESSION['filter'] = 'ga';
                    break;
                case 'all':
                default:
                    $_SESSION['filter'] = 'all';
                    break;
            }
        }

        // Ensure a default filter is set if none exists.
        if (!isset($_SESSION['filter'])) {
            $_SESSION['filter'] = 'all';
        }

        // Define the glob pattern for selecting thread files based on the filter.
        switch ($_SESSION['filter']) {
            case 'te':
                $pattern = '/*_te.csv';
                break;
            case 'en':
                $pattern = '/*_en.csv';
                break;
            case 'tr':
                $pattern = '/*_tr.csv';
                break;
            case 'ga':
                $pattern = '/*_ga.csv';
                break;
            default:
                $pattern = '/*.csv';
                break;
        }

        // Retrieve all thread CSV files matching the pattern from the thread directory.
        $threadFiles = glob($_SESSION['thread_path'] . $pattern);

        // If no thread files are found, display a message.
        if ($threadFiles === false || count($threadFiles) === 0) {
            echo "No threads found.";
        } else {
            // Loop through each thread file in reverse order (newest threads first).
            foreach (array_reverse($threadFiles) as $filePath) {
                // Extract the file name from the full file path.
                $fileName = basename($filePath);

                // Create an instance of the ThreadComponent with the filename.
                $thread = new ThreadComponent($fileName);

                // Render the thread, which will parse and display the thread content.
                $thread->render();
            }
        }
        ?>
    </div>
</div>

<!-- Render the footer section using the footer component -->
<?php $footer->render(); ?>

</body>
</html>
