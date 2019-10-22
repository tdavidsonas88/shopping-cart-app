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

    /** @var float */
    private $cartsTotal;

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
            $existingCartProduct = $this->findCartProductById($id);
            if ($existingCartProduct) {
                $this->updateProduct($existingCartProduct, $product);
            } else {
                $this->addProduct($product);
            }
        } else if ($quantity <= -1) {
            $this->removeProduct($product->getId());
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

    private function addProduct(Product $product)
    {
        $this->cart->getProducts()->add($product);
    }

    private function removeProduct(string $id)
    {
        $existingCartProduct = $this->findCartProductById($id);
        if ($existingCartProduct) {
            $this->cart->getProducts()->removeElement($existingCartProduct);
        }
    }

    private function updateProduct(Product $existingCartProduct, Product $product)
    {
        $existingCartProduct->setCurrency($product->getCurrency());
        $existingCartProduct->setName($product->getName());
        $existingCartProduct->setPrice($product->getPrice());
        $existingCartProduct->setQuantity($product->getQuantity());
        return $existingCartProduct;
    }

    /**
     * @param $id
     * @param $existingCartProduct
     * @return ArrayCollection|mixed
     */
    private function findCartProductById($id)
    {
        /** @var ArrayCollection $existingCartProduct */
        $existingCartProducts = $this->cart->getProducts()->filter(
            function (Product $product) use ($id) {
                return $product->getId() === $id;
            });
        $existingCartProduct = $existingCartProducts->first();
        return $existingCartProduct;
    }

    public function calculateCartsTotal()
    {
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            // todo: pabaigti
        }
    }

}