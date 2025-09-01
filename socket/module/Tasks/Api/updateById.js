
const updateById = async (taskId, taskData) => {

    console.log('UpdateById called with taskId:', taskId, 'and taskData:', taskData);

    try {
        if (!taskId || !taskData) {
            console.error('Task ID and data are required');
            return;
        }
        const response = await fetch(`/api/tasks/update?id=${taskId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(taskData)
        });
        const data = await response.json();
        if (data.success) {
            console.log('Task updated successfully:', data.data);
            // return data.data;
        } else {
            throw new Error(data.message || 'Failed to update task');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

export default updateById;