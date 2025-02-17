const textarea = document.getElementById('autoResize');
textarea.addEventListener('input', function () {
    // Reset the height to recalculate correctly when text is removed
    this.style.height = 'auto';
    // Set the height based on the scroll height (content height)
    this.style.height = this.scrollHeight + 'px';
});