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

    private function removeProducts(string $id, int $quantity)
    {
        /** @var Product $existingCartProduct */
        $existingCartProduct = $this->findCartProductById($id);
        if ($existingCartProduct) {
            $existingQuantity = $existingCartProduct->getQuantity();
            $leftQuantity = $existingQuantity - $quantity;
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
     * @param $id
     * @param $existingCartProduct
     * @return ArrayCollection|mixed
     */
    private function findIdenticalCartProduct(Product $newProduct)
    {
        /** @var ArrayCollection $existingCartProduct */
        $existingCartProducts = $this->cart->getProducts()->filter(
            function (Product $product) use ($newProduct) {
                return $product->getId() === $newProduct->getId()
                    && $product->getCurrency() === $newProduct->getCurrency()
                    && $product->getPrice() === $newProduct->getPrice()
                    && $product->getName() === $newProduct->getName();
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
     * @return float|int
     */
    public function calculateCartsTotalInDefaultCurrency()
    {
        $this->cartsTotal = 0;
        /** @var ArrayCollection|Product[] $products */
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            $this->cartsTotal += CartServiceHelper::calculateProductPrice($product);
        }
        return $this->cartsTotal;
    }

}