// Get the textarea element with the id "autoResize"
const textarea = document.getElementById('autoResize');

// Add an event listener for the 'input' event to auto-resize the textarea as the user types
textarea.addEventListener('input', function () {
    // Reset the height to 'auto' to recalculate the height correctly when text is removed
    this.style.height = 'auto';
    // Set the height of the textarea based on its scrollHeight, which is the total height of the content
    this.style.height = this.scrollHeight + 'px';
});
