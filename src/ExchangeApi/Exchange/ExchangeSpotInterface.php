<?php

namespace ExchangeApi\Exchange;

use Exception;
use ExchangeApi\Result\Spot\BalanceSpotResult;
use ExchangeApi\Result\Spot\OrderSpotResult;
use OrderNotFoundException;

interface ExchangeSpotInterface
{
    /**
     * @return BalanceSpotResult[]
     */
    public function getBalances(): array;

    /**
     * @param string $id
     * @param string $symbol
     * @return OrderSpotResult
     * @throws OrderNotFoundException | Exception
     */
    public function getOrder(string $id, string $symbol): OrderSpotResult;
}