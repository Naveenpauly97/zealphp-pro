import { deleteTask, handleMessage, test } from '/module/Tasks.js';

const socketUrl = 'ws://localhost:9502';
var taskWS = new WebSocket(socketUrl);
var isCoonected = false;
// console.log("dsdsd",taskWS);
const currentStatus = new URLSearchParams(window.location.search).get('status') || '';

init();

function callfunction(fun) {
    fun();
}
send({
    action: 'get_tasks',
    status: currentStatus
});

function init() {
    taskWS.onopen = () => {
        isCoonected = true;
        console.log('WebSocket connected');
        showConnectionStatus('Connected', 'success');

        // Request initial task data
        send({
            action: 'get_tasks',
            status: currentStatus
        });
    };

    taskWS.onmessage = (event) => {
        // console.log('WebSocket message received:', JSON.parse(event.data).type);
        // console.log('WebSocket message received:', JSON.parse(event.data).data.tasks);
        handleMessage(JSON.parse(event.data));
        // console.log('WebSocket received and ended test:', JSON.parse(event.data).data);
    };

    taskWS.onclose = () => {
        // console.log('WebSocket disconnected');
        showConnectionStatus('Disconnected', 'error');
        // attemptReconnect();
    };

    taskWS.onerror = (error) => {
        // console.error('WebSocket error:', error);
        showConnectionStatus('Connection Error', 'error');
    };

}

function showConnectionStatus(message, type) {
    // You can implement a connection status indicator here
    // console.log(`Connection Status: ${message} (${type})`);
}
function send(data) {
    // console.log("Task send as completed successfully ", data);
    if (taskWS && taskWS.readyState === WebSocket.OPEN) {
        taskWS.send(JSON.stringify(data));
    } else {
        // console.warn('WebSocket not connected');
        // showNotification('Connection lost. Please refresh the page.', 'error');
        errorHandler('WebSocket not connected');
    }
}