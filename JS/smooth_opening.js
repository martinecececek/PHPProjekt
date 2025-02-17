document.querySelectorAll('.thread-header-container').forEach(function (header) {
    // Add a click event listener to each thread header container.
    header.addEventListener('click', function (e) {
        // If the clicked element is the "see-more" button, do nothing.
        if (e.target.classList.contains('see-more')) return;

        // Find the closest parent element with the class 'thread'
        const threadContainer = header.closest('.thread');
        // Within that thread, locate the container that holds the replies.
        const repliesContainer = threadContainer.querySelector('.thread-replies');

        // Toggle open/close state with dynamic height adjustment for smooth animation.
        if (repliesContainer.style.maxHeight && repliesContainer.style.maxHeight !== '0px') {
            // If the replies container is open (maxHeight is set and not zero), then close it.
            repliesContainer.style.maxHeight = '0px';
            // Adjust the padding to reduce extra space when closed.
            repliesContainer.style.padding = '0 15px';
        } else {
            // If the replies container is closed, set its maxHeight to its scrollHeight.
            // This makes the container expand to fit all its content.
            repliesContainer.style.maxHeight = repliesContainer.scrollHeight + 'px';
            // Restore the padding to provide spacing when open.
            repliesContainer.style.padding = '15px';
        }
    });
});
