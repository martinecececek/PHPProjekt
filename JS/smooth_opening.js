document.querySelectorAll(
    '.thread-header-container').forEach(function (header) {
    header.addEventListener('click', function (e) {
        if (e.target.classList.contains('see-more')) return;

        const threadContainer = header.closest('.thread');
        const repliesContainer = threadContainer.querySelector('.thread-replies');

// Toggle open/closed with dynamic height
        if (repliesContainer.style.maxHeight && repliesContainer.style.maxHeight !== '0px') {
            repliesContainer.style.maxHeight = '0px';
            repliesContainer.style.padding = '0 15px';
        } else {
// Set maxHeight to scrollHeight for smooth animation
            repliesContainer.style.maxHeight = repliesContainer.scrollHeight + 'px';
            repliesContainer.style.padding = '15px';
        }
    });
});