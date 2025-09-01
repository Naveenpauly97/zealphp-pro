/**
 * WebSocket connection management
 * Handles connection, reconnection, and basic socket operations
 */

import { logger } from '/wsscript/utils/logger';

class SocketConnection {
    constructor() {
        this.socket = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 1000;
        this.messageHandlers = new Map();
        this.connectionHandlers = [];
        this.disconnectionHandlers = [];
    }

    connect(url = null) {
        // Default to current host with WebSocket port
        const wsUrl = url || `ws://${window.location.hostname}:9502`;
        
        try {
            logger.info('Attempting to connect to WebSocket server', wsUrl);
            this.socket = new WebSocket(wsUrl);
            
            this.socket.onopen = (event) => {
                this.isConnected = true;
                this.reconnectAttempts = 0;
                logger.success('WebSocket connection established');
                this.connectionHandlers.forEach(handler => handler(event));
            };

            this.socket.onmessage = (event) => {
                // logger.debug('Message received', event.data);
                this.handleMessage(event);
            };

            this.socket.onclose = (event) => {
                this.isConnected = false;
                logger.warn('WebSocket connection closed', { code: event.code, reason: event.reason });
                this.disconnectionHandlers.forEach(handler => handler(event));
                this.attemptReconnect();
            };

            this.socket.onerror = (error) => {
                logger.error('WebSocket error occurred', error);
            };

        } catch (error) {
            logger.error('Failed to create WebSocket connection', error);
        }
    }

    disconnect() {
        if (this.socket && this.isConnected) {
            logger.info('Manually disconnecting WebSocket');
            this.socket.close();
        }
    }

    send(data) {
        if (!this.isConnected || !this.socket) {
            logger.warn('Cannot send message: WebSocket not connected');
            return false;
        }

        try {
            const message = typeof data === 'string' ? data : JSON.stringify(data);
            this.socket.send(message);
            logger.debug('Message sent', message);
            return true;
        } catch (error) {
            logger.error('Failed to send message', error);
            return false;
        }
    }

    handleMessage(event) {
        try {
            // Try to parse as JSON first
            let data;
            try {
                data = JSON.parse(event.data);
            } catch {
                data = event.data; // Use as string if not JSON
            }

            // Check for message type handlers
            if (data.type && this.messageHandlers.has(data.type)) {
                this.messageHandlers.get(data.type)(data);
            } else {
                // Default handler for all messages
                if (this.messageHandlers.has('*')) {
                    this.messageHandlers.get('*')(data);
                }
            }
        } catch (error) {
            logger.error('Error handling message', error);
        }
    }

    onMessage(type, handler) {
        this.messageHandlers.set(type, handler);
    }

    onConnect(handler) {
        this.connectionHandlers.push(handler);
    }

    onDisconnect(handler) {
        this.disconnectionHandlers.push(handler);
    }

    attemptReconnect() {
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            logger.error('Max reconnection attempts reached');
            return;
        }

        this.reconnectAttempts++;
        const delay = this.reconnectDelay * this.reconnectAttempts;
        
        logger.info(`Attempting to reconnect in ${delay}ms (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
        
        setTimeout(() => {
            this.connect();
        }, delay);
    }

    getConnectionState() {
        return {
            isConnected: this.isConnected,
            reconnectAttempts: this.reconnectAttempts,
            readyState: this.socket ? this.socket.readyState : WebSocket.CLOSED
        };
    }
}

// Create singleton instance
const socketConnection = new SocketConnection();

// Helper function for easy connection
function connectSocket(url = null) {
    socketConnection.connect(url);
    return socketConnection;
}

export { SocketConnection, socketConnection, connectSocket };