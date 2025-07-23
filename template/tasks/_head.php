<? use ZealPHP\App; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - ZealPHP Tasks</title>
    <?
    $page = $page ?? '';
    App::render('/tasks' . '/' . $page . '_style')
        ?>
</head>