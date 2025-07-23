<!-- Footer Java script Section -->
<script>
    function markCompleted(taskId) {
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
                    location.reload();
                } else {
                    alert('Failed to update task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update task');
            });
    }

    function deleteTask(taskId) {
        fetch(`/api/tasks/delete?id=${taskId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to delete task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete task');
            });
    }
</script>