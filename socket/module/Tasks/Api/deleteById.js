// import { socketConnection } from "../../exports";

import { logger } from "../../exports";
import TaskController from "../Controller/TaskController";
import TaskHandler from "../TaskHandler";

async function deleteById(taskId) {
    logger.debug("Deleting task with ID:", taskId);
    // logger.debug("Current status before deletion:", currentStatus);
    const currentStatus = document.querySelector('.filters a.btn-primary')?.id || null;
    logger.debug("TaskHandler.getByStatus called with currentStatus:", currentStatus);
    try {
        const response = await fetch(`/api/tasks/delete?id=${taskId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (data.success) {
            // TaskController.showNotification(data.message, 'success');
            logger.debug("Task deleted successfully:", data);
            logger.debug("Current status after deletion:", currentStatus);
            TaskController.showNotification('Task Deleted', 'success');
            TaskHandler.getByStatus(currentStatus);
            // const message = {
            //     action: 'get_tasks',
            //     html: data.html,
            //     current_status: data.current_status
            // };
            // socketConnection.send(message)
        } else {
            TaskController.showNotification('Not deleted', 'error');
        }
    } catch (error) {
        console.error('Error deleting task:', error);
        TaskController.showNotification('Failed to delete task', 'error');
    }
}

export default deleteById;