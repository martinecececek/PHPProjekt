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
        console.log('JavaScript loaded');
        // Select all thread header containers
        const threadHeaders = document.querySelectorAll('.thread-header-container');

        threadHeaders.forEach(function (header) {
            header.addEventListener('click', function (e) {
                console.log('Thread header clicked');
                // Prevent default behavior in case a label click toggles a hidden checkbox
                e.preventDefault();

                // Locate the parent .thread and its .thread-replies
                const threadContainer = header.closest('.thread');
                const repliesContainer = threadContainer.querySelector('.thread-replies');

                if (repliesContainer) {
                    repliesContainer.classList.toggle('expanded');
                    console.log('Toggled replies. Expanded:', repliesContainer.classList.contains('expanded'));
                } else {
                    console.log('No replies container found!');
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Process each thread's replies
        const threadRepliesContainers = document.querySelectorAll('.thread-replies');

        threadRepliesContainers.forEach(function (container) {
            // Get all individual replies within the container
            const replies = container.querySelectorAll('.reply');

            // Only proceed if there are more than 4 replies
            if (replies.length > 4) {
                // Hide all replies after the fourth one (index 0-3 are the first four)
                for (let i = 4; i < replies.length; i++) {
                    replies[i].classList.add('hidden');
                }

                // Create a "See more" button
                const seeMoreBtn = document.createElement('button');
                seeMoreBtn.textContent = "See more replies";
                seeMoreBtn.classList.add('see-more-btn');

                // Append the button after the replies container
                container.appendChild(seeMoreBtn);

                // Add click event to toggle the hidden replies
                seeMoreBtn.addEventListener('click', function () {
                    // Get currently hidden replies within this container
                    const hiddenReplies = container.querySelectorAll('.reply.hidden');
                    if (hiddenReplies.length > 0) {
                        // If there are hidden replies, reveal all
                        hiddenReplies.forEach(function (reply) {
                            reply.classList.remove('hidden');
                        });
                        seeMoreBtn.textContent = "Show less replies";
                    } else {
                        // Otherwise, hide all replies after the fourth again
                        replies.forEach(function (reply, index) {
                            if (index >= 4) {
                                reply.classList.add('hidden');
                            }
                        });
                        seeMoreBtn.textContent = "See more replies";
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
