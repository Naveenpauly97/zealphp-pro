import { socketConnection } from "../../connection/socket";
import { logger } from "/wsscript/utils/logger";
// import { logger } from "/../../utils/logger";

// sample filter: 
//      { key: 'status', value: 'pending' } 
//      { key: 'status', value: 'all' }
//      { key: 'task_id', value: '1' }  


const GetTask = (filter) => {
    const message = {
        action: 'get_tasks',
        key: filter.key === 'status' ? 'status' : 'task_id',
        filter: filter.value,
        timestamp: Date.now()
    };

    logger.debug('GetTask message:', message);
    if (socketConnection.send(message)) {
        logger.debug('Get task request sent', message);
    } else {
        logger.error('Failed to send create task request', message);
    }
}

export default GetTask;