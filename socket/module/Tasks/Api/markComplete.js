import TaskController from "../Controller/TaskController";
import TaskHandler from "../TaskHandler";

function markComplete(taskId) {
    console.log("taskid ", taskId);
    const currentStatus = document.querySelector('.filters a.btn-primary')?.id || null;
    fetch(`/api/tasks/update?id=${taskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: 'completed' })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                TaskController.showNotification('Task Completed', 'success');
                TaskHandler.getByStatus(currentStatus);
            } else {
                showNotification('Task Completed Failed', 'success');
            }
        })
        .catch(error => {
            setTimeout(() => {
                console.error('Error:', error);
                showNotification('Task Completed Failed', 'error');
            }, 1500);
            location.reload();
        });
}

export default markComplete;