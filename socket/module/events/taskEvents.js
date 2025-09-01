/**
 * Task-related WebSocket event handlers
 * Manages task operations through WebSocket communication
 */

import { socketConnection } from '/wsscript/connection/socket';
import { logger } from '/wsscript/utils/logger';

class TaskEvents {
    constructor() {
        this.taskHandlers = new Map();
        this.setupEventHandlers();
    }

    setupEventHandlers() {
        // Listen for task-related messages
        socketConnection.onMessage('task_created', (data) => {
            logger.success('Task created', data);
            this.triggerHandler('created', data);
        });

        socketConnection.onMessage('task_updated', (data) => {
            logger.info('Task updated', data);
            this.triggerHandler('updated', data);
        });

        socketConnection.onMessage('task_deleted', (data) => {
            logger.info('Task deleted', data);
            this.triggerHandler('deleted', data);
        });

        socketConnection.onMessage('task_error', (data) => {
            logger.error('Task operation error', data);
            this.triggerHandler('error', data);
        });
    }

    // Task operations
    createTask(taskData) {
        const message = {
            type: 'create_task',
            data: taskData,
            timestamp: Date.now()
        };

        if (socketConnection.send(message)) {
            logger.debug('Create task request sent', taskData);
        }
    }

    updateTask(taskId, updates) {
        const message = {
            type: 'update_task',
            taskId: taskId,
            data: updates,
            timestamp: Date.now()
        };

        if (socketConnection.send(message)) {
            logger.debug('Update task request sent', { taskId, updates });
        }
    }

    deleteTask(taskId) {
        const message = {
            type: 'delete_task',
            taskId: taskId,
            timestamp: Date.now()
        };

        if (socketConnection.send(message)) {
            logger.debug('Delete task request sent', taskId);
        }
    }
    

    getTaskByStatus(status , currentStatus = 'all') {
    const condition = status === 'all' ? '' : `status=${status}`;
    fetch(`/api/tasks?${condition}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.data);
                showNotification("Data Fetched ✅", 'success');
                socketConnection.send({
                    action: 'get_tasks',
                    status: currentStatus
                });
            } else {
                showNotification(data, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // location.reload();
        });
}

    getTask(taskId) {
        const message = {
            type: 'get_task',
            taskId: taskId,
            timestamp: Date.now()
        };

        if (socketConnection.send(message)) {
            logger.debug('Get task request sent', taskId);
        }
    }

    listTasks(filters = {}) {
        const message = {
            type: 'list_tasks',
            filters: filters,
            timestamp: Date.now()
        };

        if (socketConnection.send(message)) {
            logger.debug('List tasks request sent', filters);
        }
    }

    // Event handler registration
    onTaskCreated(handler) {
        this.taskHandlers.set('created', handler);
    }

    onTaskUpdated(handler) {
        this.taskHandlers.set('updated', handler);
    }

    onTaskDeleted(handler) {
        this.taskHandlers.set('deleted', handler);
    }

    onTaskError(handler) {
        this.taskHandlers.set('error', handler);
    }

    triggerHandler(event, data) {
        if (this.taskHandlers.has(event)) {
            try {
                this.taskHandlers.get(event)(data);
            } catch (error) {
                logger.error(`Error in task ${event} handler`, error);
            }
        }
    }
}

// Create singleton instance
const taskEvents = new TaskEvents();

// Export individual functions for easier use
function handleTaskCreate(taskData) {
    console.log("Creating task:", taskData);
    
    taskEvents.createTask(taskData);
}

function handleTaskUpdate(taskId, updates) {
    taskEvents.updateTask(taskId, updates);
}

function handleTaskDelete(taskId) {
    taskEvents.deleteTask(taskId);
}

function handleTaskGet(taskId) {
    taskEvents.getTask(taskId);
}

function handleTaskGetByStatus(status) {
    taskEvents.getTaskByStatus(status);
}

function handleTaskList(filters = {}) {
    taskEvents.listTasks(filters);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

export { 
    TaskEvents, 
    taskEvents, 
    handleTaskCreate, 
    handleTaskUpdate, 
    handleTaskDelete, 
    handleTaskGet, 
    handleTaskGetByStatus, 
    handleTaskList 
};