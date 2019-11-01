<?php

use Data\Cart;
use Data\Product;
use Service\CartService;
use PHPUnit\Framework\TestCase;
use Service\ProductPrice;

/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 18.59
 */
class CartServiceTest extends TestCase
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

    /** @var CartService */
    private $cartService;
    /** @var Cart */
    private $cart;

    protected function setUp(): void
    {
        $this->cart = new Cart();
        $productPrice = new ProductPrice(self::VALIUTOS_MAP);
        $this->cartService = new CartService($this->cart, $productPrice);
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
        $total = $this->cartService->updateCartsTotalInDefaultCurrency();
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
        $total = $this->cartService->updateCartsTotalInDefaultCurrency();
        $this->assertSame(323.11157894736846, $total);

        //mbp,Macbook Pro;5,100.09,GBP
        $id = "mbp";
        $name = "Macbook Pro";
        $quantity = 5;
        $price = "100.09";
        $currency = "GBP";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(3, $this->cart->getProducts()->count());
        $total = $this->cartService->updateCartsTotalInDefaultCurrency();
        $this->assertSame(891.8047607655503, $total);


        //zen;Asus Zenbook;-1;;
        $id = "zen";
        $name = "Asus Zenbook";
        $quantity = -1;
        $price = "";
        $currency = "";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(3, $this->cart->getProducts()->count());
        $total = $this->cartService->updateCartsTotalInDefaultCurrency();
        $this->assertSame(804.0942344497608, $total);

        //len;Lenovo P1;8;60.33;USD
        $id = "len";
        $name = "Lenovo P1";
        $quantity = 8;
        $price = "60.33";
        $currency = "USD";
        $product = new Product($id, $name, $quantity, $price, $currency);
        $this->cartService->upsertProduct($product);
        $this->assertSame(4, $this->cart->getProducts()->count());
        $total = $this->cartService->updateCartsTotalInDefaultCurrency();
        $this->assertSame(1227.4626555023924, $total);
    }




}
