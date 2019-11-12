<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.23
 * Time: 04.45
 */

namespace Service;


use Data\Cart;
use Data\Product;
use Doctrine\Common\Collections\ArrayCollection;

class CartPriceCalculator
{
    /** @var float[] */
    private $valiutosMap;

    /** @var Cart */
    private $cart;

    /**
     * CartPriceCalculator constructor.
     * @param array $valiutosMap
     * @param Cart $cart
     */
    public function __construct(Cart $cart, array $valiutosMap)
    {
        $this->valiutosMap = $valiutosMap;
        $this->cart = $cart;
    }

    /**
     * @param Product $product
     * @return float|int
     * @throws \Exception
     */
    public function calculateProductPrice(Product $product)
    {
        /** @var float $productCurrency */
        $productCurrency = $this->valiutosMap[$product->getCurrency()];
        if ($productCurrency != 0) {
            $price = $product->getQuantity() * $product->getPrice() / $productCurrency;
        } else {
            throw new \Exception('Division by zero not allowed');
        }
        return $price;
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function updateCartsTotalInDefaultCurrency()
    {
        $cartsTotal = 0;
        /** @var ArrayCollection|Product[] $products */
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            $cartsTotal += $this->calculateProductPrice($product);
        }
        return $cartsTotal;
    }
}