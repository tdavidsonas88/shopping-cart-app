<?php

use Data\Cart;
use Data\Product;
use Service\CartService;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 18.59
 */
class ProductServiceTest extends TestCase
{

    /** @var CartService */
    private $cartService;

    /** @var Cart */
    private $cart;

    protected function setUp(): void
    {
        $this->cartService = new CartService();
        $this->cart = $this->cartService->getCart();
    }

    /**
     * @throws Exception
     */
    public function testUpsertProductWhenQuantityIsOneOrMore()
    {
//        mbp;Macbook Pro;2;29.99;EUR
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = 1;
        $price = "29.99";
        $currency = "EUR";

        /** @var Cart $cart */
        $this->cartService->upsertProduct(
          $id, $name, $quantity, $price, $currency
        );
        /** @var Cart $cart */
        $cart = $this->cartService->getCart();

        $this->assertSame(1, $cart->getProducts()->count());
        /** @var Product $product */
        $product = $cart->getProducts()->first();
        $this->assertSame($id, $product->getId());
        $this->assertSame($name, $product->getName());
        $this->assertSame($quantity, $product->getQuantity());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($currency, $product->getCurrency());
    }

    public function testUpsertProductWhenQuantityIsMinusOneOrLess()
    {
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = -1;
        $price = "29.99";
        $currency = "EUR";

        /** @var Product $product */
        $product = $this->cartService->upsertProduct(
            $id, $name, $quantity, $price, $currency
        );

        $this->assertSame($id, $product->getId());
        $this->assertSame($name, $product->getName());
        $this->assertSame($quantity, $product->getQuantity());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($currency, $product->getCurrency());
    }



}
