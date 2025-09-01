
window.addEventListener('error', function (event) {
    if (event.message && event.message.includes('TaskHandler is not defined')) {
        console.warn('TaskHandler missing, reloading page...');
        location.reload();
    }
});