import './bootstrap';
import './logiflow';

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    console.log('LogiFlow Application Loaded');
    
    // Initialize any global functionality here
    initializeApp();
});

function initializeApp() {
    // Global app initialization
    setupCSRFToken();
    setupAjaxDefaults();
}

function setupCSRFToken() {
    // Setup CSRF token for all AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
}

function setupAjaxDefaults() {
    // Setup Axios defaults
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}