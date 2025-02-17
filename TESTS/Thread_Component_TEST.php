<?php

use PHPUnit\Framework\TestCase;

class NewThreadTest extends TestCase
{
    private $threadsDir = './Database/Threads';

    protected function setUp()
    {
        // Ensure the threads directory exists
        if (!file_exists($this->threadsDir)) {
            mkdir($this->threadsDir, 0777, true);
        }

        // Clean up any existing thread files
        foreach (glob("$this->threadsDir/*") as $file) {
            unlink($file);
        }

        // Start a session if not already started
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Clear session data
        $_SESSION = [];
    }

    public function testThreadCreation()
    {
        // Set the username in the session
        $_SESSION['username'] = 'testuser';

        // Simulate a POST request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['threadTitle'] = 'Test Thread Title';
        $_POST['threadContent'] = 'This is a test thread content.';
        $_POST['threadCategory'] = '_te'; // technology category

        // Capture the output and headers (optional)
        ob_start();
        include 'new_thread.php';  // Include the script under test
        ob_end_clean();

        // Look for a newly created file matching the naming pattern
        $files = glob("$this->threadsDir/*_te.csv");
        $this->assertNotEmpty($files, 'No thread file was created.');

        // For testing purposes, use the first file found
        $filePath = $files[0];
        $fileContent = file_get_contents($filePath);

        // The file should have two lines: one with the thread data and one with the separator
        $lines = explode(PHP_EOL, trim($fileContent));
        $this->assertCount(2, $lines, 'File does not contain exactly two lines.');

        // The first line should have the username, title, time, and content separated by " | "
        $dataParts = explode(" | ", $lines[0]);
        $this->assertCount(4, $dataParts, 'The thread data line does not contain four parts.');
        $this->assertEquals('testuser', $dataParts[0], 'Username does not match.');
        $this->assertEquals('Test Thread Title', $dataParts[1], 'Thread title does not match.');
        $this->assertEquals('This is a test thread content.', $dataParts[3], 'Thread content does not match.');

        // The second line should be the separator line
        $this->assertEquals('-------', $lines[1], 'Separator line is incorrect.');
    }

    protected function tearDown()
    {
        // Clean up by removing all files created in the threads directory
        foreach (glob("$this->threadsDir/*") as $file) {
            unlink($file);
        }
    }
}
