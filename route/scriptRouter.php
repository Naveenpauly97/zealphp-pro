<?

use ZealPHP\App;

$app = App::instance();

// $app->route('/module/{fileName}',['methods' => ['GET']] ,function($fileName) {
//     header('Content-Type: application/javascript; charset=utf-8');
//     readfile(App::$cwd."/src/WebSocket/js/".$fileName);
// });

$app->nsPathRoute('/wsscript', "{path}", ['methods' => ['GET']], function ($path) {
    $file = App::$cwd . "/socket/module/" . $path;

    // If path doesn’t end with .js, add it
    if (!str_ends_with($file, '.js')) {
        $file .= '.js';
    }

    // Debug: Log file requests
    // error_log("[JS Module Request] $file");

    if (!file_exists($file)) {
        http_response_code(404);
        header('Content-Type: text/plain');
        echo "// Not found: $file";
        return;
    }

    // Send correct headers
    header('Content-Type: application/javascript; charset=utf-8');
    header('Cache-Control: no-cache'); // Optional: disable browser caching for testing

    // Read file safely
    $fp = fopen($file, 'rb');
    if ($fp) {
        // Optional: lock to prevent race during rapid reads
        if (flock($fp, LOCK_SH)) {
            fpassthru($fp);
            flock($fp, LOCK_UN);
        } else {
            http_response_code(503);
            echo "// File locked: $file";
        }
        fclose($fp);
    } else {
        http_response_code(500);
        echo "// Cannot open file: $file";
    }
});
