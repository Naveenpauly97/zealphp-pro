<?
use ZealPHP\App;
?>

<!DOCTYPE html>
<html lang="en">
<? App::render('/tasks/_head', ['title' => $title, 'page' => 'createPage']);
?>

<body>
    <? App::render('/tasks/_header', ['user' => $user]); ?>
    
    <? App::render('/tasks/createPageContent'); ?>

</body>

</html>