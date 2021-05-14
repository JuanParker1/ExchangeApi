<?php

namespace ExchangeApi\Result\Spot;

class SpotOrderBookEntry
{
    private float $price;
    private float $quantity;

    public function __construct(float $price, float $timestamp)
    {
        $this->price = $price;
        $this->quantity = $timestamp;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}