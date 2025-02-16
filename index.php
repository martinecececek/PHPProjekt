<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['username'];
$_SESSION['thread_path'] = './Database/Threads'; // path to csv thread dir
$_SESSION['users_path'] = './Database/Users/users.csv'; // path to csv users dir
$_SESSION['file_names'] = ['']; // array of all thread files
$_SESSION['filter'] = '';

require_once 'Components/headerComponent.php';
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
    <title>Martineckovo forum</title>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Main script loaded");

        // 1. Thread open/close toggle:
        document.querySelectorAll('.thread-header-container').forEach(function (header) {
            header.addEventListener('click', function (e) {
                // If the click is on the see-more button, don't toggle the thread
                if (e.target.classList.contains('see-more')) {
                    return;
                }
                const threadContainer = header.closest('.thread');
                const repliesContainer = threadContainer.querySelector('.thread-replies');
                repliesContainer.classList.toggle('expanded');
                console.log("Toggled thread open/close");
            });
        });

        // 2. See More Replies (limit to 4):
        document.querySelectorAll('.thread-replies').forEach(function (container) {
            // If we've already added a see-more button, skip this container
            if (container.dataset.seeMoreAdded === 'true') {
                console.log("Skipping container; see-more button already added:", container);
                return;
            }

            // Mark as processed
            container.dataset.seeMoreAdded = 'true';

            const replies = container.querySelectorAll('.reply');
            if (replies.length > 4) {
                // Hide replies from index 4 onward
                for (let i = 4; i < replies.length; i++) {
                    replies[i].classList.add('hidden');
                }

                // Create the "See more replies" button
                const seeMoreBtn = document.createElement('button');
                seeMoreBtn.classList.add('send-reply-btn', 'see-more');
                seeMoreBtn.textContent = 'See more replies';

                // Insert the button immediately after the fourth reply (index 3)
                replies[3].insertAdjacentElement('afterend', seeMoreBtn);
                console.log("Inserted see-more button after reply #4");

                // Toggle the extra replies on button click
                seeMoreBtn.addEventListener('click', function (e) {
                    e.stopPropagation(); // Don't toggle the entire thread
                    if (seeMoreBtn.textContent === 'See more replies') {
                        container.querySelectorAll('.reply.hidden').forEach(function (reply) {
                            reply.classList.remove('hidden');
                        });
                        seeMoreBtn.textContent = 'Show less replies';
                        console.log("Showing hidden replies");
                    } else {
                        for (let i = 4; i < replies.length; i++) {
                            replies[i].classList.add('hidden');
                        }
                        seeMoreBtn.textContent = 'See more replies';
                        console.log("Re-hiding extra replies");
                    }
                });
            }
        });
    });
</script>


<!-- Footer -->
<?php $footer->render(); ?>

</body>
</html>
