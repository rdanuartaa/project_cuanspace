import './bootstrap';
// public/js/app.js
window.showLoginPrompt = function() {
    document.getElementById("loginPromptModal").style.display = "flex";
};

window.closeModal = function() {
    document.getElementById("loginPromptModal").style.display = "none";
};
