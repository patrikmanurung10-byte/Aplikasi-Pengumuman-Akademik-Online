// Main JavaScript file for APAO Polibatam
// Common functions and utilities

// Utility functions
function showAlert(message, type = 'info') {
    alert(message);
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    return form.checkValidity();
}

// Common event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('APAO Polibatam - System loaded');
});