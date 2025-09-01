

import create from "./Api/create";
import deleteById from "./Api/deleteById";
import markComplete from "./Api/markComplete";
import updateById from "./Api/updateById";
import TaskController from "./Controller/TaskController";
import TaskService from "./Service/TaskService";
import { socketConnection } from "/wsscript/connection/socket";
import { logger } from "/wsscript/utils/logger";

socketConnection.onMessage('*', (data) => {
    const message = typeof data === 'string' ? data : JSON.stringify(data);
    TaskController.updateFilterButton(data);
    logger.debug("📨 Server message:", message);
});

socketConnection.onMessage('task_update', (data) => {
    // console.log("only tesing task_update", data);
    TaskController.updateTaskListContainer(data.html);
    TaskController.updateFilterButton(data);
});

const handlecheck = () => {
    console.log("handlecheck called");

}

const TaskHandler = {
    handlecheck: handlecheck,
    editTask: (taskId = null) => socketConnection.isConnected ? TaskController.createPagePopUp(taskId) : window.location.href = `/tasks/${taskId}/edit`,
    updateEditTextArea: TaskController.updateEditTextArea,
    getByStatus: (status) => socketConnection.isConnected ? TaskService.getTaskByStatus(status) : window.location.href = status === 'all' ? `/tasks` : `/tasks?status=${status}`,
    deleteById: deleteById,
    markComplete: markComplete,
    createCard: () => socketConnection.isConnected ? TaskController.createPagePopUp() : window.location.href = `/tasks/create`,
    createTask: TaskController.createNewTask,
}

export default TaskHandler;

window.TaskHandler = TaskHandler;