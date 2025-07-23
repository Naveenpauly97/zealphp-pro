<?
use ZealPHP\App;
?>

<!DOCTYPE html>
<html lang="en">
<? App::render('_head', ['title' => $title, 'page' => 'taskListPage']);
?>

<body>
    <? App::render('_header', ['user' => $user]); ?>

    <? App::render('listPageContent', ['tasks' => $task, 'stats' => $stats, 'overdue_tasks' => $overdue_tasks, 'current_status' => $current_status]); ?>

    <? App::render('_footer'); ?>

</body>

</html>