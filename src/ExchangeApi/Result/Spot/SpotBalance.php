<?php

namespace ExchangeApi\Result\Spot;

class SpotBalance
{
    private string $asset;
    private float $free;
    private float $locked;
    private float $total;

    public function __construct(string $asset, float $free, float $locked, float $total)
    {
        $this->asset = $asset;
        $this->free = $free;
        $this->locked = $locked;
        $this->total = $total;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getLocked(): float
    {
        return $this->locked;
    }

    public function getAsset(): string
    {
        return $this->asset;
    }

    public function getFree(): float
    {
        return $this->free;
    }
}