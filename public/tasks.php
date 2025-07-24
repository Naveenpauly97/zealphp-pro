<?php


use ZealPHP\App;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$taskModel = new TaskService();
// TODO : replace with actual user ID from authentication

$user = [
    'user_id' => 1,
    'username' => 'john_doe',
    'email' => 'john@gmail.com'
];

if (isset($_GET['status'])) {
    $current_status = $_GET['status'];
    $task = $taskModel->getTasksByStatus(1, $current_status);
} else {
    $task = $taskModel->getAllTasks(1);
}

$stats = $taskModel->getTaskStats(1);
$overdue_tasks = $taskModel->getOverdueTasks(1);

if (!$task) {
    elog('No tasks found for user ID 1', 'info : TaskService getAllTasks');
    $task = [];
}
App::render('taskListPage', [
    'title' => 'Task Dashboard',
    'description' => 'Manage your tasks efficiently',
    'user' => $user,
    'task' => $task,
    'stats' => $stats,
    'overdue_tasks' => $overdue_tasks,
    'current_status' => $current_status,
]);