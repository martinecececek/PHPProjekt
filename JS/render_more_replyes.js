document.addEventListener('DOMContentLoaded', function () {
    console.log("Main script loaded");

    // 1. Thread open/close toggle:
    // Loop through each thread header container and add a click event listener
    document.querySelectorAll('.thread-header-container').forEach(function (header) {
        header.addEventListener('click', function (e) {
            // If the click target is the "see-more" button, do not toggle the thread
            if (e.target.classList.contains('see-more')) {
                return;
            }
            // Find the closest parent element with the class 'thread'
            const threadContainer = header.closest('.thread');
            // Within this thread, select the container that holds the replies
            const repliesContainer = threadContainer.querySelector('.thread-replies');
            // Toggle the 'expanded' class to show or hide replies
            repliesContainer.classList.toggle('expanded');
            console.log("Toggled thread open/close");
        });
    });

    // 2. See More Replies (limit to 4):
    // Process each container holding thread replies
    document.querySelectorAll('.thread-replies').forEach(function (container) {
        // Check if a "see-more" button has already been added to avoid duplicates
        if (container.dataset.seeMoreAdded === 'true') {
            console.log("Skipping container; see-more button already added:", container);
            return;
        }

        // Mark this container as processed
        container.dataset.seeMoreAdded = 'true';

        // Select all reply elements within this container
        const replies = container.querySelectorAll('.reply');
        if (replies.length > 4) {
            // Hide all replies from index 4 onward
            for (let i = 4; i < replies.length; i++) {
                replies[i].classList.add('hidden');
            }

            // Create the "See more replies" button
            const seeMoreBtn = document.createElement('button');
            seeMoreBtn.classList.add('send-reply-btn', 'see-more');
            seeMoreBtn.textContent = 'See more replies';

            // Insert the button immediately after the fourth reply (at index 3)
            replies[3].insertAdjacentElement('afterend', seeMoreBtn);
            console.log("Inserted see-more button after reply #4");

            // Add a click event listener to the "See more replies" button
            seeMoreBtn.addEventListener('click', function (e) {
                // Prevent the click event from propagating to the parent (prevents toggling the entire thread)
                e.stopPropagation();
                if (seeMoreBtn.textContent === 'See more replies') {
                    // If button text indicates hidden replies, show them
                    container.querySelectorAll('.reply.hidden').forEach(function (reply) {
                        reply.classList.remove('hidden');
                    });
                    // Change the button text to indicate that replies can be hidden again
                    seeMoreBtn.textContent = 'Show less replies';
                    console.log("Showing hidden replies");
                } else {
                    // Otherwise, hide the extra replies again (from index 4 onward)
                    for (let i = 4; i < replies.length; i++) {
                        replies[i].classList.add('hidden');
                    }
                    // Reset the button text back to "See more replies"
                    seeMoreBtn.textContent = 'See more replies';
                    console.log("Re-hiding extra replies");
                }
            });
        }
    });
});
