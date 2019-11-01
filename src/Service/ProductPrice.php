<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.23
 * Time: 04.45
 */

namespace Service;


use Data\Product;

class ProductPrice
{
    /** @var float[] */
    private $valiutosMap;

    /**WW
     * CartServiceHelper constructor.
     * @param float[] $valiutosMap
     */
    public function __construct(array $valiutosMap)
    {
        $this->valiutosMap = $valiutosMap;
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
}