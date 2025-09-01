/**
 * Public API exports for WebSocket functionality
 * This file defines what gets exposed to the global scope
 */

import findById from './Tasks/Api/findById';
import updateById from './Tasks/Api/updateById';
import TaskHandler from './Tasks/TaskHandler';
import TaskService from './Tasks/Service/TaskService';
import { connectSocket, socketConnection } from '/wsscript/connection/socket';
import {
    handleTaskCreate,
    handleTaskUpdate,
    handleTaskDelete,
    handleTaskGet,
    handleTaskGetByStatus,
    handleTaskList,
    taskEvents
} from '/wsscript/events/taskEvents';
import { logger } from '/wsscript/utils/logger';

// Main WebSocket API object
const WebSocketAPI = {
    // Connection methods
    connect: connectSocket,
    disconnect: () => socketConnection.disconnect(),
    send: (data) => socketConnection.send(data),
    getConnectionState: () => socketConnection.getConnectionState(),
    TaskHandler: TaskHandler,

    // Task operations
    // tasks: {
    //     create: handleTaskCreate,
    //     update: handleTaskUpdate,
    //     delete: handleTaskDelete,
    //     get: handleTaskGet,
    //     getByStatus: handleTaskGetByStatus,
    //     list: handleTaskList,

    //     // Event listeners
    //     onCreated: (handler) => taskEvents.onTaskCreated(handler),
    //     onUpdated: (handler) => taskEvents.onTaskUpdated(handler),
    //     onDeleted: (handler) => taskEvents.onTaskDeleted(handler),
    //     onError: (handler) => taskEvents.onTaskError(handler)
    // },

    Task: {
        getByTaskId: TaskService.getTaskById,
        getByStatus: TaskService.getTaskByStatus,
        create: TaskService.createTask,
        api: {
            findById: findById,
            updateById: updateById
        }
    },
    // Connection event listeners
    onConnect: (handler) => socketConnection.onConnect(handler),
    onDisconnect: (handler) => socketConnection.onDisconnect(handler),
    onMessage: (type, handler) => socketConnection.onMessage(type, handler),

    // Utilities
    logger: logger
};

// Individual exports for direct import
export {
    connectSocket,
    socketConnection,
    handleTaskCreate,
    handleTaskUpdate,
    handleTaskDelete,
    handleTaskGet,
    handleTaskGetByStatus,
    handleTaskList,
    taskEvents,
    logger,
    WebSocketAPI
};

// Default export
export default WebSocketAPI;