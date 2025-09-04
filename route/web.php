<?php

use App\Controllers\LandingController;
use ZealPHP\App;
use function ZealPHP\elog;

$app = App::instance();

// Landing page route
$app->route('/', ['methods' => ['GET']], function () {
    elog('Session id ' . session_id());
    elog('Landing page accessed');
    $controller = new LandingController();
    elog('Landing page accessed Completed');
    $controller->index();
    elog('END Thanks');
    elog('session' . json_encode($_SESSION));
});

// Contact form submission (web form)
$app->route('/contact', ['methods' => ['POST']], function () {
    elog('contact post session' . json_encode($_SESSION));
    elog('Contact form submitted POST ' . json_encode($_POST));
    $controller = new LandingController();
    $controller->contact();
});

// Contact form submission (web form)
$app->route('/contact/lead-capture', ['methods' => ['GET']], function () {
    elog('contact/lead-capture  GET session' . json_encode($_SESSION));
    App::render('/landing/contact', [
        'title' => 'Yuthi',
        'description' => 'A simple PHP framework',
        'csrf_token' => $_SESSION['csrf_token'] ?? null,
        'success_message' => $_SESSION['success_message'] ?? null,
        'errors' => $_SESSION['errors'] ?? [],
        'form_data' => $_SESSION['form_data'] ?? [],
        'button' => "Home"
    ]);
});

// Contact API endpoint
$app->route('/api/contact', ['methods' => ['POST']], function () {
    $controller = new LandingController();
    return $controller->contactApi();
});
