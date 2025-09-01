<?
use ZealPHP\App;

use function ZealPHP\elog;

?>

<!DOCTYPE html>
<html lang="en">
<? App::render('/common/ui/_head', ['title' => $title, 'page' => 'taskListPage']);
?>

<body>
    <? App::render('_header', ['user' => $user]); ?>

    <?
    App::render(
        'listPageContent',
        [
            'tasks' => $task,
            'stats' => $stats,
            'overdue_tasks' => $overdue_tasks,
            'current_status' => $current_status
        ]
    );
    ?>

    <div id="create-wrapper" class="popup task-popup">
        <div class="popup-content">
            <span id="cr-close-popup" class="close-btn close-popup">&times;</span>
            <?
            App::render('createPageContent');
            ?>
        </div>
    </div>

    <div id="edit-wrapper" class="popup task-popup">
        <div class="popup-content">
            <span id="ed-close-popup" class="close-btn close-popup">&times;</span>
            <?
            App::render('editPageContent');
            ?>
        </div>
    </div>

    <? App::render('_footer', ['enable_websocket' => true]); ?>

</body>

</html>