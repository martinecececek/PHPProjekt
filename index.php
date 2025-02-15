<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();

    $_SESSION['username'] = '';
    $_SESSION['thread_path'] = './Database/Threads'; // path to csv thread dir
    $_SESSION['users_path'] = './Database/Users/users.csv'; // path to csv users dir
    $_SESSION['file_names'] = ['']; // array of all thread files
    $_SESSION['filter'] = '';
}


include 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

require_once 'Components/footerComponent.php';
$footer = new FooterComponent();

require_once 'Components/threadComponent.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Forum with Sticky Footer</title>
    <link rel="stylesheet" href="./css/index.css?v=2"/>
</head>
<body>

<!-- Header -->
<?php $header->render(); ?>

<div class="wrapper">
    <!-- Theme Filter Buttons -->
    <form method="post" action="" class="theme-filters">
        <button type="submit" name="filter" value="all"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'all') ? 'active' : ''; ?>">🔍 All
        </button>
        <button type="submit" name="filter" value="technology"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'te') ? 'active' : ''; ?>">💻 Technology
        </button>
        <button type="submit" name="filter" value="entertainment"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'en') ? 'active' : ''; ?>">🎬
            Entertainment
        </button>
        <button type="submit" name="filter" value="travel"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'tr') ? 'active' : ''; ?>">✈️ Travel
        </button>
        <button type="submit" name="filter" value="gardening"
                class="theme-btn <?php echo ($_SESSION['filter'] === 'ga') ? 'active' : ''; ?>">🌱 Gardening
        </button>
    </form>

    <div class="forum">
        <!-- Get all filenames -->
        <?php
        echo $_SESSION['username'];

        // Update session variable if a filter is submitted
        if (isset($_POST['filter'])) {
            // Map form value to a short code for filtering
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

        // Set a default filter if not already set
        if (!isset($_SESSION['filter'])) {
            $_SESSION['filter'] = 'all';
        }

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

        $threadFiles = glob($_SESSION['thread_path'] . $pattern);

        if ($threadFiles === false || count($threadFiles) === 0) {
            echo "No threads found.";
        } else {
            // Loop through each CSV file in reverse order.
            foreach (array_reverse($threadFiles) as $filePath) {
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
