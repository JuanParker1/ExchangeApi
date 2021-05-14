<?php

namespace ExchangeApi\Result\Spot;

class SpotMarketSummary
{
    private string $symbol;
    private float $lastPrice;
    private float $bidPrice;
    private float $askPrice;
    private float $openPrice;
    private float $lowPrice;
    private float $volume;
    private float $quoteVolume;

    public function __construct(string $symbol, float $lastPrice, float $bidPrice, float $askPrice, float $openPrice, float $lowPrice, float $volume, float $quoteVolume)
    {
        $this->symbol = $symbol;
        $this->lastPrice = $lastPrice;
        $this->bidPrice = $bidPrice;
        $this->askPrice = $askPrice;
        $this->openPrice = $openPrice;
        $this->lowPrice = $lowPrice;
        $this->volume = $volume;
        $this->quoteVolume = $quoteVolume;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getLastPrice(): float
    {
        return $this->lastPrice;
    }

    public function getBidPrice(): float
    {
        return $this->bidPrice;
    }

    public function getAskPrice(): float
    {
        return $this->askPrice;
    }

    public function getOpenPrice(): float
    {
        return $this->openPrice;
    }

    public function getLowPrice(): float
    {
        return $this->lowPrice;
    }

    public function getVolume(): float
    {
        return $this->volume;
    }

    public function getQuoteVolume(): float
    {
        return $this->quoteVolume;
    }
}