<?
use ZealPHP\App;
?>

<!DOCTYPE html>
<html lang="en">
<? App::render('/common/ui/_head', ['title' => $title, 'page' => 'editPage']);
?>

<body>
    <? App::render('/tasks/_header', ['user' => $user]); ?>

    <? App::render('/tasks/editPageContent', ['task' => $task]); ?>
</body>

</html>