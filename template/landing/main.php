<?
use ZealPHP\App;
?>

<body>
    <?
    App::render('/landing/landing_section/hero');
    App::render('/landing/landing_section/cyber');
    App::render('/landing/landing_section/testimonials', [
        'testimonials' => $testimonials
    ]);
    App::render('/landing/landing_section/video-section');
    App::render('/landing/landing_section/expert-section');
    ?>
</body>