<?php

namespace ExchangeApi\Result\Spot;

class BalanceSpotResult
{
    private string $asset;
    private float $free;
    private float $locked;

    public function __construct(string $asset, float $free, float $locked)
    {
        $this->asset = $asset;
        $this->free = $free;
        $this->locked = $locked;
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