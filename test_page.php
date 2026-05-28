<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$product = app('Webkul\Product\Repositories\ProductRepository')->find(1167);

echo "Testing product: " . $product->id . " - " . $product->name . " (type: " . $product->type . ")\n\n";

// Check base_image
$baseImage = product_image()->getProductBaseImage($product);
echo "Base image type: " . gettype($baseImage) . "\n";
echo "Base image medium_url: " . ($baseImage['medium_image_url'] ?? 'MISSING') . "\n";

// Check product->base_image property
$img = $product->base_image;
echo "product->base_image type: " . gettype($img) . "\n";
if (is_object($img)) {
    echo "small_image_url exists: " . (property_exists($img, 'small_image_url') ? 'yes: ' . $img->small_image_url : 'no') . "\n";
} elseif (is_array($img)) {
    echo "is array. Keys: " . implode(', ', array_keys($img)) . "\n";
} elseif ($img === null) {
    echo "is NULL\n";
}

// Check config JSON validity
$config = app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product);
$configJson = json_encode($config);
$jsonError = json_last_error();
echo "\nConfig JSON encode error: " . ($jsonError ? json_last_error_msg() : 'none') . "\n";
echo "Config JSON length: " . strlen($configJson) . "\n";

// Check regular/final price in config
echo "Config has 'regular' key: " . (isset($config['regular']) ? 'yes' : 'no') . "\n";
echo "Config has 'final' key: " . (isset($config['final']) ? 'yes' : 'no') . "\n";
if (isset($config['regular'])) {
    echo "regular price: " . print_r($config['regular'], true) . "\n";
}

// Gallery images
$galleryImages = product_image()->getGalleryImages($product);
echo "\nGallery images count: " . count($galleryImages) . "\n";

// totalQuantity
$qty = $product->totalQuantity();
echo "totalQuantity: " . $qty . " (type: " . gettype($qty) . ")\n";
