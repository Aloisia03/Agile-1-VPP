<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use App\Model\Product;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Mocker for DB or use real DB from env
// Since I can't easily mock the whole app, I'll just check the code and maybe use a log to see what's happening.

$productModel = new Product();
$all = $productModel->getAll(1); // Test with category_id 1
echo "Category 1 count: " . count($all) . "\n";
foreach($all as $p) {
    echo "Product: " . $p['name'] . " - Category: " . $p['category_name'] . "\n";
}
