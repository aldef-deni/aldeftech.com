// Bootstrap JS
// CSRF token handling for forms
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.csrfToken = csrfToken;
}

// Alpine.js is loaded via CDN in the layout — this module is just for base setup.
