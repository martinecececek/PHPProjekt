<?php

class ThreadComponent
{
    private $file_name = '';

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
        // Build the file path using the session variable and the provided file name.
        $file_path = $_SESSION['thread_path'] . '/' . $this->file_name;

        if (!file_exists($file_path)) {
            return false;
        }

        // Read the file into an array, one line per element.
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($lines)) {
            return false;
        }

        // Find the separator line.
        $separatorIndex = array_search('-------', $lines);
        if ($separatorIndex === false) {
            // If no separator is found, assume the file contains only the thread header.
            $separatorIndex = count($lines);
        }

        // Parse the first line as the thread header.
        $threadLine = $lines[0];
        $threadParts = array_map('trim', explode('|', $threadLine));

        if (count($threadParts) < 4) {
            // Not enough parts for a valid thread header.
            return false;
        }

        $thread = [
            'username' => $threadParts[0],
            'title' => $threadParts[1],
            'datetime' => $threadParts[2],
            'content' => $threadParts[3],
        ];

        // Parse each reply (each line after the separator).
        $replies = [];
        for ($i = $separatorIndex + 1; $i < count($lines); $i++) {
            $line = $lines[$i];
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 3) {
                continue;
            }
            $replies[] = [
                'username' => $parts[0],
                'datetime' => $parts[1],
                'content' => $parts[2],
            ];
        }

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
        $data = $this->parseFile();
        if (!$data) {
            echo "Thread not found or file format is incorrect.";
            return;
        }

        $thread = $data['thread'];
        $replies = $data['replies'];

        // Create a unique id based on the file name for toggling thread display.
        $unique_id = htmlspecialchars($this->file_name);
        ?>
        <div class="thread" style="width: 600px">
            <input type="checkbox" id="thread-<?php echo $unique_id; ?>" class="toggle-thread"/>
            <div class="thread-header-container">
                <label for="thread-<?php echo $unique_id; ?>" class="thread-header">
                    <div class="thread-info">
                        <h3><?php echo htmlspecialchars($thread['title']); ?></h3>
                        <span class="thread-meta">
                            Posted by <?php echo htmlspecialchars($thread['username']); ?>,
                            <?php echo htmlspecialchars($this->Convert_unix($thread['datetime'])); ?>
                        </span>
                    </div>
                </label>
            </div>
            <div class="thread-body">
                <p><?php echo htmlspecialchars($thread['content']); ?></p>
            </div>
            <div class="thread-replies">
                <?php if (!empty($replies)): ?>
                    <?php foreach ($replies as $reply): ?>
                        <div class="reply">
                            <div class="reply-header">
                                <span class="reply-author"><?php echo htmlspecialchars($reply['username']); ?></span>
                                <span
                                    class="reply-time"><?php echo htmlspecialchars($this->Convert_unix($reply['datetime'])); ?></span>
                            </div>
                            <div class="reply-content">
                                <p><?php echo htmlspecialchars($reply['content']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No replies yet.</p>
                <?php endif; ?>


                <!-- Reply Form: Visible only when thread is expanded -->
                <form class="reply-form" action="reply_handeler.php" method="POST">
                    <input type="hidden" name="thread_file" value="<?php echo htmlspecialchars($this->file_name); ?>">
                    <input type="text" name="reply" placeholder="Type your reply here..." class="reply-input" required>

                    <?php if (empty($_SESSION['username'])): ?>
                        <!-- User is not logged in: show a link that directs to the login page -->
                        <a href="login.php" class="send-reply-btn">Reply</a>
                    <?php else: ?>
                        <!-- User is logged in: show the standard reply button -->
                        <button type="button" class="send-reply-btn">Reply</button>
                    <?php endif; ?>

                </form>
            </div>
        </div>
        <?php
    }


    public function Convert_unix($post_created)
    {
        // If $post_created is not a valid Unix timestamp, strtotime() can help:
        if (!is_numeric($post_created)) {
            $post_created = strtotime($post_created);
        }

        $posted_ago = time() - intval($post_created);

        // Debug output
        // echo "posted_ago: $posted_ago seconds<br>";

        switch (true) {
            case ($posted_ago >= 86400):
                $days = floor($posted_ago / 86400);
                return $days . " day" . ($days > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 3600):
                $hours = floor($posted_ago / 3600);
                return $hours . " hour" . ($hours > 1 ? "s" : "") . " ago";
            case ($posted_ago >= 60):
                $minutes = floor($posted_ago / 60);
                return $minutes . " minute" . ($minutes > 1 ? "s" : "") . " ago";
            case ($posted_ago < 60):
                return "less than a minute ago";
            default:
                return "ERROR: data not found";
        }
    }

}


?>
