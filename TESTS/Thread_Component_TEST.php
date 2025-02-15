<?php

use PHPUnit\Framework\TestCase;

require_once '../Components/threadComponent.php'; // Adjust the path if needed

class ThreadComponentTest extends TestCase
{
    /*** Tests for parseFile ***/

    public function testParseFileNonExistent()
    {
        $nonExistentFile = 'nonexistent_file.txt';
        $component = new ThreadComponent($nonExistentFile);
        $this->assertFalse($component->parseFile(), 'parseFile should return false for non-existent file.');
    }

    public function testParseFileEmptyFile()
    {
        $fileName = $this->createTempThreadFile('');
        $component = new ThreadComponent($fileName);
        $this->assertFalse($component->parseFile(), 'parseFile should return false for an empty file.');
    }

    public function testParseFileInvalidHeader()
    {
        // Header with less than 4 parts.
        $content = "user1 | Thread Title\n-------\nuser2 | 1670000500 | Reply content";
        $fileName = $this->createTempThreadFile($content);
        $component = new ThreadComponent($fileName);
        $this->assertFalse($component->parseFile(), 'parseFile should return false when thread header has less than 4 parts.');
    }

    public function testParseFileValidThreadOnly()
    {
        // No separator line; only thread header is provided.
        $content = "user1 | Thread Title | 1670000000 | Thread content";
        $fileName = $this->createTempThreadFile($content);
        $component = new ThreadComponent($fileName);
        $result = $component->parseFile();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('thread', $result);
        $this->assertArrayHasKey('replies', $result);

        // Check thread header data.
        $expectedThread = [
            'username' => 'user1',
            'title' => 'Thread Title',
            'datetime' => '1670000000',
            'content' => 'Thread content',
        ];
        $this->assertEquals($expectedThread, $result['thread']);

        // Expect no replies.
        $this->assertEmpty($result['replies']);
    }

    public function testParseFileValidThreadWithReplies()
    {
        $content = <<<EOD
user1 | Thread Title | 1670000000 | Thread content
-------
user2 | 1670000500 | Reply content 1
invalid reply line without proper format
user3 | 1670001000 | Reply content 2
EOD;
        $fileName = $this->createTempThreadFile($content);
        $component = new ThreadComponent($fileName);
        $result = $component->parseFile();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('thread', $result);
        $this->assertArrayHasKey('replies', $result);

        // Validate thread header.
        $expectedThread = [
            'username' => 'user1',
            'title' => 'Thread Title',
            'datetime' => '1670000000',
            'content' => 'Thread content',
        ];
        $this->assertEquals($expectedThread, $result['thread']);

        // Validate replies: only the properly formatted lines are included.
        $expectedReplies = [
            [
                'username' => 'user2',
                'datetime' => '1670000500',
                'content' => 'Reply content 1',
            ],
            [
                'username' => 'user3',
                'datetime' => '1670001000',
                'content' => 'Reply content 2',
            ],
        ];
        $this->assertEquals($expectedReplies, $result['replies']);
    }

    /*** Tests for Convert_unix ***/

    public function testConvertUnixLessThanMinute()
    {
        $component = new ThreadComponent('dummy');
        $timestamp = time() - 30; // 30 seconds ago
        $result = $component->Convert_unix($timestamp);
        $this->assertEquals("less than a minute ago", $result);
    }

    public function testConvertUnixMinutes()
    {
        $component = new ThreadComponent('dummy');
        $timestamp = time() - 120; // 2 minutes ago
        $result = $component->Convert_unix($timestamp);
        $this->assertEquals("2 minutes ago", $result);
    }

    public function testConvertUnixHours()
    {
        $component = new ThreadComponent('dummy');
        $timestamp = time() - 4000; // ~1 hour ago (4000 seconds)
        $result = $component->Convert_unix($timestamp);
        // Floor(4000/3600) should be 1
        $this->assertEquals("1 hour ago", $result);
    }

    public function testConvertUnixDays()
    {
        $component = new ThreadComponent('dummy');
        $timestamp = time() - 90000; // ~1 day ago (90000 seconds)
        $result = $component->Convert_unix($timestamp);
        $this->assertEquals("1 day ago", $result);
    }

    public function testConvertUnixStringDate()
    {
        $component = new ThreadComponent('dummy');
        // Use a string date that strtotime() can parse.
        $dateString = '2020-01-01 00:00:00';
        // Compute expected days ago.
        $timestamp = strtotime($dateString);
        $daysAgo = floor((time() - $timestamp) / 86400);
        $expected = $daysAgo . " day" . ($daysAgo > 1 ? "s" : "") . " ago";
        $result = $component->Convert_unix($dateString);
        $this->assertEquals($expected, $result);
    }

    /*** Tests for render ***/

    public function testRenderInvalidFile()
    {
        // For a file that does not exist, render should output an error message.
        $component = new ThreadComponent('nonexistent.txt');

        ob_start();
        $component->render();
        $output = ob_get_clean();

        $this->assertStringContainsString("Thread not found or file format is incorrect.", $output);
    }

    public function testRenderValidThreadLoggedOut()
    {
        // Simulate a valid thread file with header and one reply.
        $content = <<<EOD
user1 | Thread Title | 1670000000 | Thread content
-------
user2 | 1670000500 | Reply content 1
EOD;
        $fileName = $this->createTempThreadFile($content);
        // Ensure user is not logged in.
        unset($_SESSION['username']);

        $component = new ThreadComponent($fileName);

        ob_start();
        $component->render();
        $output = ob_get_clean();

        // Check that thread title, username, content, and reply details are present.
        $this->assertStringContainsString(htmlspecialchars('Thread Title'), $output);
        $this->assertStringContainsString(htmlspecialchars('user1'), $output);
        $this->assertStringContainsString(htmlspecialchars('Thread content'), $output);
        $this->assertStringContainsString(htmlspecialchars('user2'), $output);
        $this->assertStringContainsString(htmlspecialchars('Reply content 1'), $output);

        // Since user is logged out, the reply button should be a link to the login page.
        $this->assertStringContainsString('href="login.php"', $output);
    }

    public function testRenderValidThreadLoggedIn()
    {
        // Simulate a valid thread file with header and no replies.
        $content = "user1 | Thread Title | 1670000000 | Thread content";
        $fileName = $this->createTempThreadFile($content);
        // Simulate logged in user.
        $_SESSION['username'] = 'logged_in_user';

        $component = new ThreadComponent($fileName);

        ob_start();
        $component->render();
        $output = ob_get_clean();

        // Check that thread details are present.
        $this->assertStringContainsString(htmlspecialchars('Thread Title'), $output);
        $this->assertStringContainsString(htmlspecialchars('user1'), $output);
        $this->assertStringContainsString(htmlspecialchars('Thread content'), $output);

        // Since there are no replies, check that "No replies yet." is shown.
        $this->assertStringContainsString("No replies yet.", $output);

        // Check that the reply button is rendered as a button.
        $this->assertStringContainsString('<button type="button"', $output);
    }
}
