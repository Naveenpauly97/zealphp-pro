<?

use ZealPHP\App;

$app = App::instance();

// $app->route("/showNames",function(){
//     showName();
// });

$app->route('/custom/data/{id}', function() {
   showName();
});

function showName(){
    echo "This is custom api create by me";
}

