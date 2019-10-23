<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.23
 * Time: 04.45
 */

namespace Service;


use Data\Product;

class CartServiceHelper
{
    public static function calculateProductPrice(Product $product)
    {
        $price = $product->getQuantity() * $product->getPrice() / CartService::VALIUTOS_MAP[$product->getCurrency()];
        return $price;
    }
}