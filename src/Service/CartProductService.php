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

class CartProductService
{

    /** @var Cart */
    private $cart;

    /**
     * CartProductService constructor.
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
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
    public function upsertProduct(Product $product)
    {
        //  If quantity is 1 or more then product is being added/updated,
        //  if quantity is -1 or less, then product is being removed from shopping cart.
        if ($product->getQuantity() >= 1) {
            $existingCartProduct = $this->findIdenticalCartProduct($product);
            if ($existingCartProduct) {
                $this->updateProduct($existingCartProduct, $product);
            } else {
                $this->addProduct($product);
            }
        } else if ($product->getQuantity() <= -1) {
            $this->removeProducts($product->getId(), $product->getQuantity());
        } else if ($product->getQuantity() == 0) {
            throw new \Exception('Nenumatyta situacija');
        }
        return $product;
    }

    private function addProduct(Product $product)
    {
        $this->cart->getProducts()->add($product);
    }

    private function removeProducts(string $id, int $quantity)
    {
        /** @var Product $existingCartProduct */
        $existingCartProduct = $this->findCartProductById($id);
        if ($existingCartProduct) {
            $existingQuantity = $existingCartProduct->getQuantity();
            // quantity is already negative
            $leftQuantity = $existingQuantity + $quantity;
            if ($leftQuantity > 0) {
                $existingCartProduct->setQuantity($leftQuantity);
            } else {
                $this->cart->getProducts()->removeElement($existingCartProduct);
            }
        }
    }

    private function updateProduct(Product $existingCartProduct, Product $product)
    {
        $existingCartProduct->setQuantity($product->getQuantity() + 1);
        return $existingCartProduct;
    }

    /**
     *
     * @param Product $newProduct
     * @return ArrayCollection|mixed
     */
    private function findIdenticalCartProduct(Product $newProduct)
    {
        /** @var ArrayCollection $existingCartProduct */
        $existingCartProducts = $this->cart->getProducts()->filter(
            function (Product $existingProduct) use ($newProduct) {
                // data needed for comparing the floats

                return $existingProduct->getId() === $newProduct->getId()
                    && $existingProduct->getCurrency() === $newProduct->getCurrency()
                    && \Utils::floatsAreEqual($existingProduct->getPrice(), $newProduct->getPrice())
                    && $existingProduct->getName() === $newProduct->getName();
            });
        $existingCartProduct = $existingCartProducts->first();
        return $existingCartProduct;
    }

    /**
     * @param string $id
     * @return Product
     */
    private function findCartProductById(string $id)
    {
        /** @var ArrayCollection $existingCartProduct */
        $existingCartProducts = $this->cart->getProducts()->filter(
            function (Product $product) use ($id) {
                return $product->getId() === $id;
            });
        /** @var Product $existingCartProduct */
        $existingCartProduct = $existingCartProducts->first();
        return $existingCartProduct;
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

}