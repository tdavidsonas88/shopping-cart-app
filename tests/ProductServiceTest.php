<?php

use Service\ProductService;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 18.59
 */


class ProductServiceTest extends TestCase
{

    /** @var ProductService */
    private $productService;

    protected function setUp(): void
    {
        $this->productService = new ProductService();
    }

    public function testAddProduct()
    {
//        mbp;Macbook Pro;2;29.99;EUR
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = 2;
        $price = "29.99";
        $currency = "EUR";

        /** @var Product $product */
        $product = $this->productService->addProduct(
          $id, $name, $quantity, $price, $currency
        );

        $this->assertSame($id, $product->getId());
        $this->assertSame($name, $product->getName());
        $this->assertSame($quantity, $product->getQuantity());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($currency, $product->getCurrency());

    }



}
