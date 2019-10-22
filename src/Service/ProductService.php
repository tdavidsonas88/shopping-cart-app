<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 19.22
 */

namespace Service;

use Product;

class ProductService
{
    /**
     * @param $id
     * @param $name
     * @param $quantity
     * @param $price
     * @param $currency
     * @return Product
     */
    public function addProduct($id, $name, $quantity, $price, $currency)
    {
        $product = new Product($id, $name, $quantity, $price, $currency);
        return $product;
    }
}