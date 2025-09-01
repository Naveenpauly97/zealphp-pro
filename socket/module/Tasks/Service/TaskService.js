
import CreateTask from './CreateTask';
import GetTask from './GetTask';


const TaskService = {
    createTask: CreateTask,
    getTaskById: (taskId) => {
        const filter = {
            key: 'task_id',
            value: taskId
        }
        GetTask(filter);
    },
    getTaskByStatus: (status) => {
        console.log('GetTaskByStatus called with status:', status);
        const filter = {
            key: 'status',
            value: status
        }
        GetTask(filter);
    }
}

export default TaskService;