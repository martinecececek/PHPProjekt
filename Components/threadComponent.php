<?php

// The ThreadComponent class handles reading, parsing, and rendering a forum thread and its replies.
class ThreadComponent
{
    // Private variable to store the CSV filename for the thread.
    private $file_name = '';

    // Constructor that initializes the object with the given thread file name.
    public function __construct($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * Reads and parses the thread file.
     *
     * Expected file structure:
     *   username | title | DateTime | content
     *   -------
     *   username | DateTime | content
     *   username | DateTime | content
     *   ...
     *
     * @return array|false Returns an associative array with 'thread' and 'replies' keys, or false on error.
     */
    public function parseFile()
    {
        // Build the file path using the session variable 'thread_path' and the provided file name.
        $file_path = $_SESSION['thread_path'] . '/' . $this->file_name;

        // If the file does not exist, return false.
        if (!file_exists($file_path)) {
            return false;
        }

        // Read the file into an array, where each element is a line from the file.
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return false;
        }

        // Find the separator line ('-------') which divides the thread header from its replies.
        $separatorIndex = array_search('-------', $lines);
        if ($separatorIndex === false) {
            // If no separator is found, assume the file contains only the thread header.
            $separatorIndex = count($lines);
        }

        // Parse the first line as the thread header.
        $threadLine = $lines[0];
        $threadParts = array_map('trim', explode('|', $threadLine));

        // Validate that the thread header has at least 4 parts (username, title, datetime, content).
        if (count($threadParts) < 4) {
            // Not enough parts for a valid thread header.
            return false;
        }

        // Create an associative array for the thread header.
        $thread = [
            'username' => $threadParts[0],
            'title' => $threadParts[1],
            'datetime' => $threadParts[2],
            'content' => $threadParts[3],
        ];

        // Initialize an empty array to store replies.
        $replies = [];
        // Loop through each line after the separator to parse the replies.
        for ($i = $separatorIndex + 1; $i < count($lines); $i++) {
            $line = $lines[$i];
            $parts = array_map('trim', explode('|', $line));
            // Validate that each reply has at least 3 parts (username, datetime, content).
            if (count($parts) < 3) {
                continue;
            }
            $replies[] = [
                'username' => $parts[0],
                'datetime' => $parts[1],
                'content' => $parts[2],
            ];
        }

        // Return the thread header and replies.
        // Note: Replies are reversed so that the most recent reply appears first.
        return [
            'thread' => $thread,
            'replies' => array_reverse($replies)
        ];
    }

    /**
     * Renders the thread and its replies.
     */
    public function render()
    {
        // Parse the thread file to get thread data and replies.
        $data = $this->parseFile();
        if (!$data) {
            // If parsing fails, display an error message.
            echo "Thread not found or file format is incorrect.";
            return;
        }

        // Extract the thread header and replies from the parsed data.
        $thread = $data['thread'];
        $replies = $data['replies'];
        ?>
        <div class="thread" style="width: 600px">
            <!-- Thread Header Section -->
            <div class="thread-header-container">
                <label for="thread-<?php echo htmlspecialchars($this->file_name); ?>" class="thread-header">
                    <div class="thread-info">
                        <!-- Display the thread title -->
                        <h3><?php echo htmlspecialchars($thread['title']); ?></h3>
                        <span class="thread-meta">
                            <!-- Display the username and formatted posting time -->
                            Posted by <?php echo htmlspecialchars($thread['username']); ?>,
                            <?php echo htmlspecialchars($this->Convert_unix($thread['datetime'])); ?>
                        </span>
                    </div>
                </label>
            </div>
            <!-- Thread Body Section -->
            <div class="thread-body">
                <p><?php echo htmlspecialchars($thread['content']); ?></p>
            </div>
            <!-- Replies Section -->
            <div class="thread-replies">
                <?php if (!empty($replies)): ?>
                    <?php foreach ($replies as $reply): ?>
                        <div class="reply">
                            <div class="reply-header">
                                <!-- Display reply author and formatted time -->
                                <span class="reply-author"><?php echo htmlspecialchars($reply['username']); ?></span>
                                <span
                                    class="reply-time"><?php echo htmlspecialchars($this->Convert_unix($reply['datetime'])); ?></span>
                            </div>
                            <div class="reply-content">
                                <!-- Display the reply content -->
                                <p><?php echo htmlspecialchars($reply['content']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Message shown if there are no replies -->
                    <p>No replies yet.</p>
                <?php endif; ?>

                <!-- Reply Form: Visible only when thread is expanded -->
                <form class="reply-form" action="./Functions/reply_handeler.php" method="POST">
                    <!-- Hidden input to pass the thread file name to the reply handler -->
                    <input type="hidden" name="thread_file" value="<?php echo htmlspecialchars($this->file_name); ?>">
                    <!-- Text input for the user to type their reply -->
                    <input type="text" name="reply" placeholder="Type your reply here..." class="reply-input" required>
                    <?php if (empty($_SESSION['username'])): ?>
                        <!-- If user is not logged in, show a link directing to the login page -->
                        <a href="login.php" class="send-reply-btn">Reply</a>
                    <?php else: ?>
                        <!-- If user is logged in, show the submit button -->
                        <button type="submit" class="send-reply-btn">Reply</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Converts a Unix timestamp into a human-readable "time ago" format.
     *
     * @param mixed $post_created The Unix timestamp or a date string.
     * @return string A human-readable time difference.
     */
    public function Convert_unix($post_created)
    {
        // If $post_created is not a numeric Unix timestamp, attempt to convert it.
        if (!is_numeric($post_created)) {
            $post_created = strtotime($post_created);
        }

        // Calculate the time difference between now and when the post was created.
        $posted_ago = time() - intval($post_created);

        // Determine the appropriate time unit for the output.
        switch (true) {
            case ($posted_ago >= 31556926): // year(s)
                $years = floor($posted_ago / 31556926);
                return $years . " year" . ($years > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 2629743): // month(s)
                $months = floor($posted_ago / 2629743);
                return $months . " month" . ($months > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 604800): // week(s)
                $weeks = floor($posted_ago / 604800);
                return $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 86400): // day(s)
                $days = floor($posted_ago / 86400);
                return $days . " day" . ($days > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 3600): // hour(s)
                $hours = floor($posted_ago / 3600);
                return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 60): // minute(s)
                $minutes = floor($posted_ago / 60);
                return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
            case ($posted_ago < 60): // less than a minute
                return "less than a minute ago";
            default:
                return "ERROR: data not found";
        }
    }
}

?>
