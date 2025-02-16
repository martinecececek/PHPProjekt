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