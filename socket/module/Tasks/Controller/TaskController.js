import { logger } from "../../exports";
import findById from "../Api/findById";
import updateById from "../Api/updateById";
import CreateTask from "./createTask";

const createPagePopUp = async (taskId = null) => {
    console.log('taskId', taskId);
    if (taskId) {
        const task = await findById(taskId);
        updateEditTextArea(task);
    }
    const popup = document.getElementById(taskId ? 'edit-wrapper' : 'create-wrapper');
    const closeButton = document.getElementById(taskId ? 'ed-close-popup' : 'cr-close-popup');
    popup.classList.add('show');
    closeButton.addEventListener('click', function () {
        popup.classList.remove('show');
    });
    window.addEventListener('click', function (event) {
        if (event.target === popup) {
            popup.classList.remove('show');
        }
    });
}

const updateEditTextArea = (task) => {
    console.log('task', task);
    if (task) {
        document.getElementById('ed-title').value = task.title || 'edede';
        document.getElementById('ed-description').value = task.description || '';
        document.getElementById('ed-status').value = task.status || 'pending';
        document.getElementById('ed-priority').value = task.priority || 'low';
        document.getElementById('ed-due_date').value = task.due_date || '';
        document.querySelector('.edit-task-form')
            .onsubmit = async function (e) {
                e.preventDefault();
                // Gather updated values from the form
                const updatedTask = {
                    id: task.id,
                    title: document.getElementById('ed-title').value,
                    description: document.getElementById('ed-description').value,
                    status: document.getElementById('ed-status').value,
                    priority: document.getElementById('ed-priority').value,
                    due_date: document.getElementById('ed-due_date').value
                };
                await updateById(task.id, updatedTask);
                const popup = document.getElementById('edit-wrapper');
                popup.classList.remove('show');
                // const currentStatus = document.getElementsByClassName('filters');
                // let activeId = null;
                // const activeAnchor = Array.from(document.querySelectorAll('.filters a'))
                //     .filter(a => a.classList.contains('btn-primary'))[0];
                // activeId = activeAnchor ? activeAnchor.id : null;
                const activeId = document.querySelector('.filters a.btn-primary')?.id || null;
                console.log('Active anchor id:', activeId);
                wsExports.Task.getByStatus(activeId);
            };
    }

}

function updateFilterButton(message, data = null) {
    const activefilterbutton = document.querySelector('.filters a.btn-primary');
    // logger.debug('Active filter button:', activefilterbutton);
    // logger.debug('Active:', message);
    activefilterbutton.classList.remove('btn-primary');
    activefilterbutton.classList.add('btn-secondary');
    const filterbutton = document.getElementById(message.data.current_status ?? 'all');
    filterbutton.classList.remove('btn-secondary');
    filterbutton.classList.add('btn-primary');
    // logger.debug(`Active filter button Updated for status ${message.data.current_status}`, 'debug');
}

function updateTaskListContainer(html) {
    logger.info("html found");
    const container = document.getElementsByClassName('container');
    if (container.length > 0) {
        container[0].outerHTML = html;
    } else {
        logger.error('Container with id "task-grid" not found.');
    }
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


const TaskController = {
    createPagePopUp: createPagePopUp,
    updateEditTextArea: updateEditTextArea,
    updateFilterButton: updateFilterButton,
    updateTaskListContainer: updateTaskListContainer,
    showNotification: showNotification,
    createNewTask: CreateTask,
}

export default TaskController;