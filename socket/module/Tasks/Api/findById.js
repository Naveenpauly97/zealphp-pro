
const findById = async (taskId) => {
    try {
        if (!taskId) {
            console.error('Task ID is required');
            return;
        }
        const response = await fetch(`/api/tasks/get?id=${taskId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        });
        const data = await response.json();
        console.log('Task Data:', data);
        if (data.success) {
            return data.data;
        } else {
            throw new Error(data.message || 'Failed to fetch task');
        }
    } catch (error) {
        console.error('Error:', error);
        // throw error;
    }
}


export default findById;