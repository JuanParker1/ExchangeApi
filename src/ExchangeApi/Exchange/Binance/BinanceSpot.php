<?php

namespace ExchangeApi\Exchange\Binance;

use ExchangeApi\Exception\OrderNotFoundException;
use ExchangeApi\Exchange\ExchangeSpotInterface;
use ExchangeApi\Result\Spot\BalanceSpotResult;
use ExchangeApi\Result\Spot\OrderSpotResult;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

final class BinanceSpot extends AbstractBinance implements ExchangeSpotInterface
{
    private const URI = 'https://api.binance.com';
    private const BALANCE_ENDPOINT = '/api/v3/account';
    private const ORDER_BOOK_ENDPOINT = '/api/v3/depth';
    private const ORDER_ENDPOINT = '/api/v3/order';
    private const ROLLING_TICKER_ENDPOINT = '/api/v3/ticker/24hr';
    private const EXCHANGE_INFO_ENDPOINT = '/api/v3/exchangeInfo';
    private const OPEN_ORDERS_ENDPOINT = '/api/v3/openOrders';

    /**
     * @return BalanceSpotResult[]
     * @throws GuzzleException
     */
    public function getBalances(): array
    {
        $result = [];
        $response = $this->get(self::URI . self::BALANCE_ENDPOINT);

        foreach ($response['balances'] as $balance) {
            $result[] = new BalanceSpotResult($balance['asset'], $balance['free'], $balance['locked']);
        }

        return $result;
    }

    /**
     * @param string $id
     * @param string $symbol
     * @return OrderSpotResult
     * @throws GuzzleException
     * @throws OrderNotFoundException
     */
    public function getOrder(string $id, string $symbol): OrderSpotResult
    {
        $queryParams = http_build_query(array_filter(['orderId' => $id, 'symbol' => strtoupper($symbol)]));
        try {
            $response = $this->get(self::URI . self::ORDER_ENDPOINT, $queryParams);
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

        return new OrderSpotResult(
            $response['orderId'],
            $response['symbol'],
            $response['price'],
            $response['origQty'],
            $response['executedQty'] ?? 0,
            $this->translateOrderStatus($response['status']),
            $response['timeInForce'],
            $this->translateOrderType($response['type']),
            $this->translateOrderSide($response['side']),
            $response['stopPrice'] ?? 0,
            $response['time'],
        );

    }
}