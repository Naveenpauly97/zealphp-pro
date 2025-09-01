<?
use ZealPHP\App;
?>

<!DOCTYPE html>
<html lang="en">
<? App::render('/common/ui/_head', ['title' => $title??'Create pagTAsk', 'page' => 'createPage']);
?>

<body>
    <? App::render('/tasks/_header', ['user' => $user]); ?>

    <? App::render('/tasks/createPageContent'); ?>

</body>

</html>