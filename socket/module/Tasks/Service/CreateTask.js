import { socketConnection } from "../../connection/socket";
import { logger } from "/wsscript/utils/logger";
// import { logger } from "/../../utils/logger";


// TODO : CreateTask handler in server not available yet to bring on future
const CreateTask = (taskData) => {
    const message = {
        type: 'create_tasks',
        data: taskData,
        timestamp: Date.now()
    };

    if (socketConnection.send(message)) {
        logger.debug('Create task request sent', taskData);
    } else {
        logger.error('Failed to send create task request', taskData);
    }
}

export default CreateTask;