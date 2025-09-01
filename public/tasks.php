<?php


use ZealPHP\App;
use ZealPHP\G;
use ZealPHP\Models\User;
use ZealPHP\Services\AuthService;
use ZealPHP\Services\TaskService;

use function ZealPHP\elog;

$authService = new AuthService();
$taskModel = new TaskService();

$user = new User(G::instance()->session['user']);
if (!$user) {
    //elog'User not found in session', 'error : TaskService getAllTasks');
    header('Location: /login');
    exit;
}

$status = G::instance()->get['status'];
elog("GET Ordsdsds--------" . json_encode(G::instance()->get));

if (isset($status)) {
    $current_status = $status;
    $task = $taskModel->getTasksByStatus($user->id, $current_status);
    elog('tasks found for user ID ' . $user->id, 'info : TaskService getTasksByStatus');
} else {
    $task = $taskModel->getAllTasks($user->id);
    $current_status = 'all'; // TODO Changes
}

$stats = $taskModel->getTaskStats($user->id);
$overdue_tasks = $taskModel->getOverdueTasks($user->id);

if (!$task) {
    elog('No tasks found for user ID ' . $user->id, 'info : TaskService getAllTasks');
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
