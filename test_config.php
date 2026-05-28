<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$product = app('Webkul\Product\Repositories\ProductRepository')->find(1167);
if (!$product) {
    echo "Product 1167 not found, searching for a configurable product...\n";
    $product = app('Webkul\Product\Repositories\ProductRepository')
        ->findWhere(['type' => 'configurable'])
        ->first();
}

if (!$product) {
    echo "No configurable product found.\n";
    exit(1);
}

echo "Product ID: " . $product->id . "\n";
echo "Product type: " . $product->type . "\n";
echo "Product name: " . $product->name . "\n";

$config = app('Webkul\Product\Helpers\ConfigurableOption')->getConfigurationConfig($product);
echo "Config keys: " . implode(', ', array_keys($config)) . "\n";
echo "Attributes count: " . count($config['attributes'] ?? []) . "\n";

if (!empty($config['attributes'])) {
    $attr = $config['attributes'][0];
    echo "First attribute id: " . ($attr['id'] ?? 'MISSING') . "\n";
    echo "First attribute code: " . ($attr['code'] ?? 'MISSING') . "\n";
    echo "First attribute options count: " . count($attr['options'] ?? []) . "\n";

    if (!empty($attr['options'])) {
        $opt = $attr['options'][0];
        echo "First option id: " . ($opt['id'] ?? 'MISSING') . "\n";
        echo "First option label: " . ($opt['label'] ?? 'MISSING') . "\n";
    }
}

echo "\nJSON config (first 500 chars):\n";
echo substr(json_encode($config), 0, 500) . "\n";
