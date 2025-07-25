<?php


use ZealPHP\App;
use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;
use ZealPHP\Session;

use function ZealPHP\elog;

$authService = new AuthService();
$taskModel = new TaskService();

$user = $authService->getCurrentUser();
if (!$user) {
    elog('User not found in session', 'error : TaskService getAllTasks');
    header('Location: /login');
    exit;
}

if (isset($_GET['status'])) {
    $current_status = $_GET['status'];
    $task = $taskModel->getTasksByStatus($user->id, $current_status);
} else {
    $task = $taskModel->getAllTasks($user->id);
}

$stats = $taskModel->getTaskStats($user->id);
$overdue_tasks = $taskModel->getOverdueTasks($user->id);

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