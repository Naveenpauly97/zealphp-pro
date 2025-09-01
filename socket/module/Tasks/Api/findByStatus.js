import TaskController from "../Controller/TaskController";

const findByStatus = (status) => {
    const condition = status === 'all' ? '' : `?status=${status}`;
    fetch(`/api/tasks${condition}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    }).then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.data);
                showNotification("Data Fetched ✅", 'success');
                send({
                    action: 'get_tasks',
                    status: currentStatus
                });
            } else {
                TaskController.showNotification(data, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
}

export default findByStatus;