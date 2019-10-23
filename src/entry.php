<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 21.54
 */

use Data\Product;
use Service\CartService;
use Service\FileReaderService;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var string[] $lines */
$lines = FileReaderService::readFileIntoArray('input.txt');

$cartService = new CartService();

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
