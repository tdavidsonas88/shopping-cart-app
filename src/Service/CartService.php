<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 19.22
 */

namespace Service;

use Data\Cart;
use Data\Product;
use Doctrine\Common\Collections\ArrayCollection;

class CartService
{
    /** @var Cart */
    private $cart;

    /**
     * CartService constructor.
     */
    public function __construct()
    {
        $this->cart = new Cart();
    }


    /**
     * @param $id
     * @param $name
     * @param $quantity
     * @param $price
     * @param $currency
     * @return Product
     * @throws \Exception
     */
    public function upsertProduct($id, $name, $quantity, $price, $currency)
    {
        $product = new Product($id, $name, $quantity, $price, $currency);

        //  If quantity is 1 or more then product is being added/updated,
        //  if quantity is -1 or less, then product is being removed from shopping cart.
        if ($quantity >= 1) {
            /** @var ArrayCollection $existingCartProduct */
            $existingCartProduct = $this->cart->getProducts()->filter(
                function(Product $product) use ($id) {
                    return $product->getId() === $id;
                });
            if ($existingCartProduct->first()) {
                $this->updateProduct($existingCartProduct->first(), $product);
            } else {
                $this->addProduct($product);
            }
        } else if ($quantity <= -1) {
            $this->removeProduct($product);
        } else if ($quantity == 0) {
            throw new \Exception('Nenumatyta situacija');
        }
        return $product;
    }

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     */
    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function addProduct(Product $product)
    {
        $this->cart->getProducts()->add($product);
    }

    public function removeProduct(Product $product)
    {
        $this->cart->getProducts()->removeElement($product);
    }

    public function updateProduct(Product $existingCartProduct, Product $product)
    {
        $existingCartProduct->setCurrency($product->getCurrency());
        $existingCartProduct->setName($product->getName());
        $existingCartProduct->setPrice($product->getPrice());
        $existingCartProduct->setQuantity($product->setQuantity());
        return $existingCartProduct;
    }

}