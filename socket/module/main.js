import WebSocketAPI from '/wsscript/exports';

const main = () => {
    console.log("Main test called");
    WebSocketAPI.connect();
    
}

let isInitialized = false;
function initializeWebSocket(autoConnect = true) {
    if (isInitialized) {
        console.warn('WebSocket already initialized');
        return WebSocketAPI;
    }

    console.log('🚀 Initializing WebSocket module...');
    
    if (autoConnect) {
        WebSocketAPI.connect();
    }

    // Set up global error handling
    WebSocketAPI.onConnect(() => {
        console.log('✅ WebSocket module ready');
    });

    WebSocketAPI.onDisconnect(() => {
        console.log('❌ WebSocket module disconnected');
    });

    isInitialized = true;
    return WebSocketAPI;
}

// Auto-initialize when module loads
const wsAPI = initializeWebSocket();

// Expose to global scope for non-module usage
if (typeof window !== 'undefined') {
    // window.wsExports = window.wsExports || {};
    // window.wsExports.wsAPI = wsAPI;
    window.wsExports = wsAPI;
    window.WebSocketAPI = wsAPI;
}

// Export for module usage
export default wsAPI;
export {
    initializeWebSocket,
    WebSocketAPI,
    main
};


window.myApp = window.myApp || {};
window.myApp.main = main;
