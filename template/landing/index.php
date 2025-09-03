<?
use ZealPHP\App;
?>
<!DOCTYPE html>
<html lang="en">
<?
App::render('/landing/common/__head');
App::render('/landing/common/__header');
App::render('/landing/main', [
    'testimonials' => $testimonials
]);
?>

</html>