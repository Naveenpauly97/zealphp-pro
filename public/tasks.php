<?php


use ZealPHP\App;

$tasks = ["sample task", "another task", "yet another task"];

App::render('TaskListPage', [
    'title' => 'Task Dashboard',
    'description' => 'Manage your tasks efficiently',
    'tasks' => $tasks,
]);