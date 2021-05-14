<?php

namespace ExchangeApi\Exchange\Binance;

use ExchangeApi\Exception\OrderNotFoundException;
use ExchangeApi\Exchange\ExchangeSpotInterface;
use ExchangeApi\Result\Spot\SpotBalance;
use ExchangeApi\Result\Spot\SpotMarket;
use ExchangeApi\Result\Spot\SpotMarketSummary;
use ExchangeApi\Result\Spot\SpotOrder;
use ExchangeApi\Result\Spot\SpotOrderBook;
use ExchangeApi\Result\Spot\SpotOrderBookEntry;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

final class BinanceSpot extends AbstractBinance implements ExchangeSpotInterface
{
    private const ORDER_SIDE_SELL = 'SELL';
    private const ORDER_SIDE_BUY = 'BUY';
    private const ORDER_TIME_IN_FORCE_GTC = 'GTC';
    private const ORDER_TYPE_LIMIT = 'LIMIT';
    private const ORDER_RESP_TYPE_RESULT = 'RESULT';

    private const URI = 'https://api.binance.com';
    private const BALANCE_ENDPOINT = '/api/v3/account';
    private const ORDER_BOOK_ENDPOINT = '/api/v3/depth';
    private const ORDER_ENDPOINT = '/api/v3/order';
    private const ROLLING_TICKER_ENDPOINT = '/api/v3/ticker/24hr';
    private const EXCHANGE_INFO_ENDPOINT = '/api/v3/exchangeInfo';
    private const ALL_ORDERS_ENDPOINT = '/api/v3/allOrders';

    /**
     * @return SpotBalance[]
     * @throws GuzzleException
     */
    public function getBalances(): array
    {
        $result = [];
        $response = $this->get(self::URI . self::BALANCE_ENDPOINT, true);

        foreach ($response['balances'] as $balance) {
            $result[] = new SpotBalance($balance['asset'], $balance['free'], $balance['locked'], ($balance['free'] + $balance['locked']));
        }

        return $result;
    }

    /**
     * @param string $id
     * @param string $symbol
     * @return SpotOrder
     * @throws GuzzleException
     * @throws OrderNotFoundException
     */
    public function getOrder(string $id, string $symbol): SpotOrder
    {
        $queryParams = http_build_query(array_filter(['clientOrderId' => $id, 'symbol' => strtoupper($symbol)]));

        try {
            $response = $this->get(self::URI . self::ORDER_ENDPOINT, true, $queryParams);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseContent = json_decode($response->getBody()->getContents(), true);
                switch ($responseContent['code']) {
                    case -2013:
                        throw new OrderNotFoundException();
                }
            }
            throw $e;
        }

        return new SpotOrder(
            $response['clientOrderId'],
            $response['symbol'],
            $response['price'],
            $response['origQty'],
            $response['executedQty'],
            $this->translateOrderStatus($response['status']),
            $response['timeInForce'],
            $this->translateOrderType($response['type']),
            $this->translateOrderSide($response['side']),
            $response['stopPrice'] ?? 0,
            $response['time'],
        );
    }

    /**
     * @param string $symbol
     * @return SpotOrder[]
     * @throws GuzzleException
     */
    public function getOrderHistory(string $symbol): array
    {
        $result = [];
        $queryParams = http_build_query(array_filter(['symbol' => strtoupper($symbol)]));
        $response = $this->get(self::URI . self::ALL_ORDERS_ENDPOINT, true, $queryParams);

        foreach ($response as $order) {
            $result[] = new SpotOrder(
                $order['clientOrderId'],
                $order['symbol'],
                $order['price'],
                $order['origQty'],
                $order['executedQty'],
                $this->translateOrderStatus($order['status']),
                $order['timeInForce'],
                $this->translateOrderType($order['type']),
                $this->translateOrderSide($order['side']),
                $order['stopPrice'] ?? 0,
                $order['time']
            );
        }

        return $result;
    }

    /**
     * @param string $symbol
     * @param float $quantity
     * @param float $price
     * @return SpotOrder
     * @throws GuzzleException
     */
    public function createLimitBuyOrder(string $symbol, float $quantity, float $price): SpotOrder
    {
        $queryParams = http_build_query(array_filter([
            'symbol' => strtoupper($symbol),
            'quantity' => $quantity,
            'price' => $price,
            'side' => self::ORDER_SIDE_BUY,
            'type' => self::ORDER_TYPE_LIMIT,
            'timeInForce' => self::ORDER_TIME_IN_FORCE_GTC,
            'newOrderRespType' => self::ORDER_RESP_TYPE_RESULT
        ]));

        $response = $this->post(self::URI . self::ORDER_ENDPOINT, true, $queryParams);

        return new SpotOrder(
            $response['clientOrderId'],
            $response['symbol'],
            $response['price'],
            $response['origQty'],
            $response['executedQty'],
            $this->translateOrderStatus($response['status']),
            $response['timeInForce'],
            $this->translateOrderType($response['type']),
            $this->translateOrderSide($response['side']),
            0,
            $this->getMicroTime(),
        );
    }

    /**
     * @param string $symbol
     * @param float $quantity
     * @param float $price
     * @return SpotOrder
     * @throws GuzzleException
     */
    public function createLimitSellOrder(string $symbol, float $quantity, float $price): SpotOrder
    {
        $queryParams = http_build_query(array_filter([
            'symbol' => strtoupper($symbol),
            'quantity' => $quantity,
            'price' => $price,
            'side' => self::ORDER_SIDE_SELL,
            'type' => Self::ORDER_TYPE_LIMIT,
            'timeInForce' => self::ORDER_TIME_IN_FORCE_GTC,
            'newOrderRespType' => self::ORDER_RESP_TYPE_RESULT
        ]));

        $response = $this->post(self::URI . self::ORDER_ENDPOINT, true, $queryParams);

        return new SpotOrder(
            $response['clientOrderId'],
            $response['symbol'],
            $response['price'],
            $response['origQty'],
            $response['executedQty'] ?? 0,
            $this->translateOrderStatus($response['status']),
            $response['timeInForce'],
            $this->translateOrderType($response['type']),
            $this->translateOrderSide($response['side']),
            0,
            $this->getMicroTime(),
        );
    }

    /**
     * @param string $id
     * @param string $symbol
     * @return bool
     * @throws GuzzleException
     */
    public function cancelOrder(string $id, string $symbol): bool
    {
        $queryParams = http_build_query(array_filter([
            'symbol' => strtoupper($symbol),
            'origClientOrderId' => $id
        ]));

        $response = $this->delete(self::URI . self::ORDER_ENDPOINT, true, $queryParams);

        return $response['status'] === ORDER_STATUS_CANCELED;
    }

    /**
     * @param string $symbol
     * @return SpotMarketSummary
     * @throws GuzzleException
     */
    public function getMarketSummary(string $symbol): SpotMarketSummary
    {
        $queryParams = http_build_query(array_filter([
            'symbol' => strtoupper($symbol)
        ]));

        $response = $this->get(self::URI . self::ROLLING_TICKER_ENDPOINT, false, $queryParams);

        return new SpotMarketSummary(
            $response['symbol'],
            $response['lastPrice'],
            $response['bidPrice'],
            $response['askPrice'],
            $response['openPrice'],
            $response['lowPrice'],
            $response['volume'],
            $response['quoteVolume']
        );
    }

    /**
     * @return SpotMarketSummary[]
     * @throws GuzzleException
     */
    public function getMarketSummaries(): array
    {
        $result = [];
        $response = $this->get(self::URI . self::ROLLING_TICKER_ENDPOINT, false);

        foreach ($response as $summary) {
            $result[] = new SpotMarketSummary(
                $summary['symbol'],
                $summary['lastPrice'],
                $summary['bidPrice'],
                $summary['askPrice'],
                $summary['openPrice'],
                $summary['lowPrice'],
                $summary['volume'],
                $summary['quoteVolume']
            );
        }

        return $result;
    }

    /**
     * @param string $symbol
     * @param int $limit
     * @return SpotOrderBook
     * @throws GuzzleException
     */
    public function getOrderBook(string $symbol, int $limit = 500): SpotOrderBook
    {
        $queryParams = http_build_query(array_filter([
            'symbol' => strtoupper($symbol),
            'limit' => $limit
        ]));

        $response = $this->get(self::URI . self::ORDER_BOOK_ENDPOINT, false, $queryParams);

        $asks = [];
        foreach ($response['asks'] as $ask) {
            $asks[] = new SpotOrderBookEntry($ask[0], $ask[1]);
        }
        $bids = [];
        foreach ($response['bids'] as $bid) {
            $bids[] = new SpotOrderBookEntry($bid[0], $bid[1]);
        }

        return new SpotOrderBook($asks, $bids);
    }

    /**
     * @return SpotMarket[]
     * @throws GuzzleException
     */
    public function getMarkets(): array
    {
        $result = [];
        $response = $this->get(self::URI . self::EXCHANGE_INFO_ENDPOINT, false);

        foreach ($response['symbols'] as $symbol) {
            $result[] = new SpotMarket(
                $symbol['symbol'],
                $symbol['baseAsset'],
                $symbol['baseAssetPrecision'],
                $symbol['quoteAsset'],
                $symbol['quoteAssetPrecision'],
            );
        }

        return $result;
    }
}