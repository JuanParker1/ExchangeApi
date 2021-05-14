<?php

namespace ExchangeApi\Result\Spot;

class SpotMarket
{
    private string $symbol;
    private string $baseAsset;
    private int $baseAssetPrecision;
    private string $quoteAsset;
    private int $quoteAssetPrecision;

    public function __construct(string $symbol, string $baseAsset, int $baseAssetPrecision, string $quoteAsset, int $quoteAssetPrecision)
    {
        $this->symbol = $symbol;
        $this->baseAsset = $baseAsset;
        $this->baseAssetPrecision = $baseAssetPrecision;
        $this->quoteAsset = $quoteAsset;
        $this->quoteAssetPrecision = $quoteAssetPrecision;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getBaseAsset(): string
    {
        return $this->baseAsset;
    }

    public function getBaseAssetPrecision(): int
    {
        return $this->baseAssetPrecision;
    }

    public function getQuoteAsset(): string
    {
        return $this->quoteAsset;
    }

    public function getQuoteAssetPrecision(): int
    {
        return $this->quoteAssetPrecision;
    }
}