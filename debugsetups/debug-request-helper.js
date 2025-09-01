/**
 * Browser helper to maintain Xdebug session
 * Add this script to your browser console or as a bookmarklet
 */

// Set Xdebug session cookie
document.cookie = "XDEBUG_SESSION=VSCODE; path=/";

// Add Xdebug trigger to all requests
const originalFetch = window.fetch;
window.fetch = function(...args) {
    if (args[1]) {
        args[1].headers = args[1].headers || {};
        args[1].headers['Cookie'] = 'XDEBUG_SESSION=VSCODE';
    } else {
        args[1] = {
            headers: {
                'Cookie': 'XDEBUG_SESSION=VSCODE'
            }
        };
    }
    return originalFetch.apply(this, args);
};

console.log('Xdebug session helper activated');