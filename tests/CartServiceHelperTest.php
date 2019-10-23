<?php

use Data\Product;
use PHPUnit\Framework\TestCase;
use Service\CartServiceHelper;

/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.23
 * Time: 04.47
 */


class CartServiceHelperTest extends TestCase
{

    public function testCalculateProductPrice()
    {
        $id = "zen";
        $name = "Asus Zenbook";
        $quantity = 3;
        $price = "99.99";
        $currency = "USD";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $price = CartServiceHelper::calculateProductPrice($product);
        $this->assertSame(263.13157894736844, $price);
    }
}
