<?php
/**
 * Created by PhpStorm.
 * User: tadas
 * Date: 19.10.22
 * Time: 18.57
 */

namespace Data;

class Product
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var int */
    private $quantity;
    /** @var float */
    private $price;
    /** @var string */
    private $currency;

    /**
     * Product constructor.
     * @param int $id
     * @param string $name
     * @param int $quantity
     * @param float $price
     * @param string $currency
     */
    public function __construct($id, $name, $quantity, $price, $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = intval($quantity);
        $this->price = floatval($price);
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
}