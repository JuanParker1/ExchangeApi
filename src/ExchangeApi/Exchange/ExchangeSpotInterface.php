<?php

namespace ExchangeApi\Exchange;

use ExchangeApi\Exception\OrderNotFoundException;
use ExchangeApi\Result\Spot\SpotBalance;
use ExchangeApi\Result\Spot\SpotMarket;
use ExchangeApi\Result\Spot\SpotMarketSummary;
use ExchangeApi\Result\Spot\SpotOrder;
use ExchangeApi\Result\Spot\SpotOrderBook;

interface ExchangeSpotInterface
{
    /**
     * @return SpotBalance[]
     */
    public function getBalances(): array;

    /**
     * @param string $id
     * @param string $symbol
     * @return SpotOrder
     * @throws OrderNotFoundException
     */
    public function getOrder(string $id, string $symbol): SpotOrder;

    /**
     * @param string $symbol
     * @return SpotOrder[]
     */
    public function getOrderHistory(string $symbol): array;

    /**
     * Returns the OrderId as string
     *
     * @param string $symbol
     * @param float $quantity
     * @param float $price
     * @return SpotOrder
     */
    public function createLimitBuyOrder(string $symbol, float $quantity, float $price): SpotOrder;

    /**
     * Returns the OrderId as string
     *
     * @param string $symbol
     * @param float $quantity
     * @param float $price
     * @return SpotOrder
     */
    public function createLimitSellOrder(string $symbol, float $quantity, float $price): SpotOrder;

    /**
     * @param string $id
     * @param string $symbol
     * @return bool
     */
    public function cancelOrder(string $id, string $symbol): bool;

    /**
     * @param string $symbol
     * @return SpotMarketSummary
     */
    public function getMarketSummary(string $symbol): SpotMarketSummary;

    /**
     * @return SpotMarketSummary[]
     */
    public function getMarketSummaries(): array;

    /**
     * @return SpotMarket[]
     */
    public function getMarkets(): array;

    /**
     * @param string $symbol
     * @param int $limit
     * @return SpotOrderBook
     */
    public function getOrderBook(string $symbol, int $limit = 500): SpotOrderBook;
}