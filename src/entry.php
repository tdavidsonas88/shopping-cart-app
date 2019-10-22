<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 21.54
 */

use Service\CartService;
use Service\FileReaderService;

//require_once('Service/FileReaderService.php');
//require_once('Service/CartService.php');

require_once __DIR__ . '/../vendor/autoload.php';

/** @var string[] $lines */
$lines = FileReaderService::readFileIntoArray('input.txt');

foreach ($lines as $line) {
    list($id, $name, $quantity, $price, $currency) = explode(";", $line);

    $cartService = new CartService();
    try {
        $cartService->upsertProduct($id, $name, $quantity, $price, $currency);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    /** @var \Data\Cart $cart */
    $cart = $cartService->getCart();

    print_r($cart);
}
