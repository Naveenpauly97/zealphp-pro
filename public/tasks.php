<?php


use ZealPHP\App;
use ZealPHP\Services\TaskService;
use function ZealPHP\elog;

$taskModel = new TaskService();
// TODO : replace with actual user ID from authentication
$task = $taskModel->getAllTasks(1);

if (!$task) {
    elog('No tasks found for user ID 1', 'info : TaskService getAllTasks');
    $task = [];
}
App::render('TaskListPage', [
    'title' => 'Task Dashboard',
    'description' => 'Manage your tasks efficiently',
    'task' => $task,
]);