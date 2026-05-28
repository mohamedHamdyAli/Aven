<?php
echo PHP_VERSION . "\n";
$x = null;
try {
    $v = $x->foo ?? 'default';
    echo "Result: " . $v . "\n";
} catch (Throwable $e) {
    echo "Error (" . get_class($e) . "): " . $e->getMessage() . "\n";
}

// Also test with trigger_error suppression
set_error_handler(function($errno, $errstr) {
    echo "Warning caught: $errstr\n";
    return true;
});

$y = null;
$val = $y->property ?? 'fallback';
echo "With error handler, result: " . $val . "\n";
