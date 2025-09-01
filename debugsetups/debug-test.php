<?php
/**
 * Test file for debugging ZealPHP
 */

require_once __DIR__ . '/vendor/autoload.php';

use ZealPHP\G;
use function ZealPHP\elog;

// Test function with breakpoint opportunities
function testDebugFunction($param1, $param2) {
    //elog"Debug function called with params: $param1, $param2");
    
    $result = $param1 + $param2;
    
    // Set a breakpoint here to inspect variables
    $debugInfo = [
        'param1' => $param1,
        'param2' => $param2,
        'result' => $result,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    return $debugInfo;
}

// Test the function
$testResult = testDebugFunction(10, 20);
print_r($testResult);

echo "Debug test completed!\n";