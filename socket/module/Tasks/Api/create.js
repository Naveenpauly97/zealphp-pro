
const create = async (taskData) => {

    console.log('Create called with taskData:', taskData);

    try {
        if (!taskData) {
            console.error('Task ID and data are required');
            return;
        }
        const response = await fetch(`/api/tasks/create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(taskData)
        });
        const data = await response.json();
        if (data.success) {
            console.log('Task created successfully:', data.data);
            // return data.data;
        } else {
            throw new Error(data.message || 'Failed to update task');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

export default create;