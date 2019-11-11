<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 21.54
 */

use Data\Cart;
use Data\Product;
use Service\CartService;
use Service\FileReaderService;
use Service\ProductPrice;

require_once __DIR__ . '/vendor/autoload.php';



/** @var string[] $lines */
$lines = FileReaderService::readFileIntoArray('src/input.txt');

$cart = new Cart();
$productPrice = new ProductPrice(Product::VALIUTOS_MAP);

$cartService = new CartService($cart, $productPrice);

foreach ($lines as $line) {
    list($id, $name, $quantity, $price, $currency) = explode(";", str_replace(array(","), ";",$line));
    $product = new Product($id, $name, $quantity, $price, $currency);
    try {
        $cartService->upsertProduct($product);
        $cartsTotal = $cartService->updateCartsTotalInDefaultCurrency();
        echo $cartsTotal;
        echo "\n";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
