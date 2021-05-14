<?php

namespace ExchangeApi\Result\Spot;

class SpotOrderBookEntry
{
    private float $price;
    private float $quantity;

    public function __construct(float $price, float $quantity)
    {
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }
}