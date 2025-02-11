<?php
session_start();
$_SESSION['username'] = 'admin';

require_once 'Components/headerComponent.php';
$header = new HeaderCopoment("Discusion forum");

require_once 'Components/footerComponent.php';
$footer = new FooterComponent();
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
    <div class="wrapper">
        <!-- Header -->
        <?php $header->render(); ?>

        <!-- Forum Content -->
        <div class="forum">
            <!-- Thread 1 -->
            <div class="thread">
                <input type="checkbox" id="thread-1" class="toggle-thread" />
                <div class="thread-header-container">
                    <label for="thread-1" class="thread-header">
                        <div class="thread-info">
                            <h3>Welcome to the Forum</h3>
                            <span class="thread-meta">Posted by Admin, 3 hours ago</span>
                        </div>
                    </label>
                    <button type="button" class="reply-btn">Reply</button>
                </div>
                <div class="thread-body">
                    <p>This is the first thread. Click on the header to view replies.</p>
                </div>
                <div class="thread-replies">
                    <div class="reply">
                        <div class="reply-header">
                            <span class="reply-author">Alice</span>
                            <span class="reply-time">2 hours ago</span>
                        </div>
                        <div class="reply-content">
                            <p>Thank you for setting up this forum!</p>
                        </div>
                    </div>
                    <div class="reply">
                        <div class="reply-header">
                            <span class="reply-author">Bob</span>
                            <span class="reply-time">1 hour ago</span>
                        </div>
                        <div class="reply-content">
                            <p>Looking forward to great discussions.</p>
                        </div>
                    </div>
                    <!-- Reply Form: Visible only when thread is expanded -->
                    <div class="reply-form">
                        <textarea placeholder="Type your reply here..."></textarea>
                        <button type="button" class="send-reply-btn">Send Reply</button>
                    </div>
                </div>
            </div>

            <!-- Thread 2 -->
            <div class="thread">
                <input type="checkbox" id="thread-2" class="toggle-thread" />
                <div class="thread-header-container">
                    <label for="thread-2" class="thread-header">
                        <div class="thread-info">
                            <h3>Project Ideas</h3>
                            <span class="thread-meta">Posted by Jane, 5 hours ago</span>
                        </div>
                    </label>
                    <button type="button" class="reply-btn">Reply</button>
                </div>
                <div class="thread-body">
                    <p>Share your ideas for our upcoming school project.</p>
                </div>
                <div class="thread-replies">
                    <div class="reply">
                        <div class="reply-header">
                            <span class="reply-author">Chris</span>
                            <span class="reply-time">4 hours ago</span>
                        </div>
                        <div class="reply-content">
                            <p>I think we should build an eco-friendly device.</p>
                        </div>
                    </div>
                    <!-- Reply Form -->
                    <div class="reply-form">
                        <textarea placeholder="Type your reply here..."></textarea>
                        <button type="button" class="send-reply-btn">Send Reply</button>
                    </div>
                </div>
            </div>

            <!-- Thread 3 -->
            <div class="thread">
                <input type="checkbox" id="thread-3" class="toggle-thread" />
                <div class="thread-header-container">
                    <label for="thread-3" class="thread-header">
                        <div class="thread-info">
                            <h3>Study Group Formation</h3>
                            <span class="thread-meta">Posted by Mark, Yesterday</span>
                        </div>
                    </label>
                    <button type="button" class="reply-btn">Reply</button>
                </div>
                <div class="thread-body">
                    <p>Let's form study groups for the upcoming exams.</p>
                </div>
                <div class="thread-replies">
                    <div class="reply">
                        <div class="reply-header">
                            <span class="reply-author">Emma</span>
                            <span class="reply-time">Yesterday</span>
                        </div>
                        <div class="reply-content">
                            <p>Count me in!</p>
                        </div>
                    </div>
                    <div class="reply">
                        <div class="reply-header">
                            <span class="reply-author">Lucas</span>
                            <span class="reply-time">Yesterday</span>
                        </div>
                        <div class="reply-content">
                            <p>I'm available too. Let's coordinate.</p>
                        </div>
                    </div>
                    <!-- Reply Form -->
                    <div class="reply-form">
                        <textarea placeholder="Type your reply here..."></textarea>
                        <button type="button" class="send-reply-btn">Send Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php $footer->render(); ?>
</body>
</html>
