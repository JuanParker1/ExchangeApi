<?php

namespace ExchangeApi\Result\Spot;

class SpotOrderBook
{
    private array $asks;
    private array $bids;

    public function __construct(array $asks, array $bids)
    {
        $this->asks = $asks;
        $this->bids = $bids;
    }

    /**
     * @return SpotOrderBookEntry[]
     */
    public function getAsks(): array
    {
        return $this->asks;
    }

    /**
     * @return SpotOrderBookEntry[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }
}