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
class CartServiceTest extends TestCase
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
    public function testUpsertProduct()
    {
        $this->assertSame(0, $this->cart->getProducts()->count());

        //zen;Asus Zenbook;3;99.99;USD
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = 2;
        $price = "29.99";
        $currency = "EUR";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(1, $this->cart->getProducts()->count());
        $total = $this->updateCartsTotalInDefaultCurrency();
        $this->assertSame(2*$price, $total);

        //zen;Asus Zenbook;3;99.99;USD
        $id = "zen";
        $name = "Asus Zenbook";
        $quantity = 3;
        $price = "99.99";
        $currency = "USD";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(2, $this->cart->getProducts()->count());
        $total = $this->updateCartsTotalInDefaultCurrency();
        $this->assertSame(323.11157894736846, $total);

        //mbp,Macbook Pro;5,100.09,GBP
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = 5;
        $price = "100.09";
        $currency = "GBP";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(2, $this->cart->getProducts()->count());
        $total = $this->updateCartsTotalInDefaultCurrency();
        $this->assertSame(891.804760765, $total);


        //zen;Asus Zenbook;-1;;
        $id = "zen";
        $name = "Asus Zenbook";
        $quantity = -1;
        $price = "";
        $currency = "";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(2, $this->cart->getProducts()->count());
        $total = $this->updateCartsTotalInDefaultCurrency();
        $this->assertSame(804.094234449, $total);

        //len;Lenovo P1;8;60.33;USD
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = 5;
        $price = "100.09";
        $currency = "GBP";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(3, $this->cart->getProducts()->count());
        $total = $this->updateCartsTotalInDefaultCurrency();
        $this->assertSame(891.804760765, $total);
    }

    /**
     * @return float|int
     */
    private function updateCartsTotalInDefaultCurrency()
    {
        return $this->cartService->calculateCartsTotalInDefaultCurrency();
    }




}
