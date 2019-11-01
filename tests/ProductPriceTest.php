<?php

use Data\Product;
use PHPUnit\Framework\TestCase;
use Service\ProductPrice;

/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.23
 * Time: 04.47
 */


class ProductPriceTest extends TestCase
{

    //    EUR:USD - 1:1.14, EUR:GBP - 1:0.88
    const USD = 'USD';
    const GBP = 'GBP';
    const EUR = 'EUR';

    const DEFAULT_CURRENCY = self::EUR;

    const VALIUTOS_MAP = [
        self::EUR => 1,
        self::USD => 1.14,
        self::GBP => 0.88
    ];

    /**
     * @throws Exception
     */
    public function testCalculateProductPrice()
    {
        $id = "zen";
        $name = "Asus Zenbook";
        $quantity = 3;
        $price = "99.99";
        $currency = "USD";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $productPrice = new ProductPrice(self::VALIUTOS_MAP);

        $price = $productPrice->calculateProductPrice($product);
        $this->assertSame(263.13157894736844, $price);
    }
}
