/**
 * Logger utility for WebSocket operations
 * Provides consistent logging across the WebSocket application
 */

class Logger {
    constructor(prefix = 'WS') {
        this.prefix = prefix;
        this.isDebugEnabled = true; // Can be configured based on environment
    }

    info(message, data = null) {
        console.log(`[${this.prefix}] ℹ️ ${message}`, data || '');
    }

    success(message, data = null) {
        console.log(`[${this.prefix}] ✅ ${message}`, data || '');
    }

    error(message, error = null) {
        console.error(`[${this.prefix}] ❌ ${message}`, error || '');
    }

    debug(message, data = null) {
        if (this.isDebugEnabled) {
            console.log(`[${this.prefix}] 🐛 ${message}`, data || '');
        }
    }

    warn(message, data = null) {
        console.warn(`[${this.prefix}] ⚠️ ${message}`, data || '');
    }
}

// Create singleton instance
const logger = new Logger('WebSocket');

export { Logger, logger };