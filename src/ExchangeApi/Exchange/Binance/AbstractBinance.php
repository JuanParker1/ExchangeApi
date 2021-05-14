<?php

namespace ExchangeApi\Exchange\Binance;

use Exception;
use ExchangeApi\Exchange\AbstractExchange;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractBinance extends AbstractExchange
{
    const EXCHANGE_NAME = 'binance';

    /**
     * @param string $url
     * @param bool $mustBeSigned
     * @param string|null $queryParams
     * @return array
     * @throws GuzzleException
     */
    protected function delete(string $url, bool $mustBeSigned, ?string $queryParams = null): array
    {
        return $this->request(self::DELETE, $url, $mustBeSigned, $queryParams);
    }

    /**
     * @param string $method
     * @param string $url
     * @param bool $mustBeSigned
     * @param string|null $queryParams
     * @return array
     * @throws GuzzleException
     */
    private function request(string $method, string $url, bool $mustBeSigned, ?string $queryParams = null): array
    {
        list($headers, $url) = $this->createRequestPayload($url, $mustBeSigned, $queryParams);

        $response = $this->client->request($method, $url, $headers);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string|null $queryParams
     * @param string $url
     * @param bool $mustBeSigned
     * @return array
     */
    protected function createRequestPayload(string $url, bool $mustBeSigned, ?string $queryParams = null): array
    {
        if ($mustBeSigned) {
            list($headers, $queryString) = $this->signRequest($queryParams);
            return array($headers, $url . '?' . $queryString);
        }

        return [[], $url . '?' . $queryParams];
    }

    /**
     * @param string|null $queryParams
     * @return array
     */
    protected function signRequest(?string $queryParams = null): array
    {
        $queryString = 'timestamp=' . $this->getMicroTime() . '&recvWindow=60000';
        if (!is_null($queryParams)) $queryString .= '&' . $queryParams;
        $hash = hash_hmac('sha256', $queryString, $this->apiSecret);
        $queryString .= '&signature=' . $hash;
        $headers['headers'] = [
            'X-MBX-APIKEY' => $this->apiPublic,
            'signature' => $hash
        ];

        return [$headers, $queryString];
    }

    /**
     * @param string $url
     * @param bool $mustBeSigned
     * @param string|null $queryParams
     * @return array
     * @throws GuzzleException
     */
    protected function post(string $url, bool $mustBeSigned, ?string $queryParams = null): array
    {
        return $this->request(self::POST, $url, $mustBeSigned, $queryParams);
    }

    /**
     * @param string $url
     * @param bool $mustBeSigned
     * @param string|null $queryParams
     * @return array
     * @throws GuzzleException
     */
    protected function get(string $url, bool $mustBeSigned, ?string $queryParams = null): array
    {
        return $this->request(self::GET, $url, $mustBeSigned, $queryParams);
    }

    /**
     * @param string $orderStatus
     * @return string
     * @throws Exception
     */
    protected function translateOrderStatus(string $orderStatus): string
    {
        switch ($orderStatus) {
            case 'NEW':
                return ORDER_STATUS_NEW;
            case 'PARTIALLY_FILLED':
                return ORDER_STATUS_PARTIALLY_FILLED;
            case 'FILLED':
                return ORDER_STATUS_FILLED;
            case 'CANCELED':
                return ORDER_STATUS_CANCELED;
            case 'REJECTED':
                return ORDER_STATUS_REJECTED;
            case 'EXPIRED':
                return ORDER_STATUS_EXPIRED;
            default:
                throw new Exception(sprintf('Unknown Status %s', $orderStatus));
        }
    }

    /**
     * @param string $orderSide
     * @return string
     * @throws Exception
     */
    protected function translateOrderSide(string $orderSide): string
    {
        switch ($orderSide) {
            case 'BUY':
                return ORDER_SIDE_BUY;
            case 'SELL':
                return ORDER_SIDE_SELL;
            default:
                throw new Exception(sprintf('Unknown OrderSide %s', $orderSide));
        }
    }

    /**
     * @param string $orderType
     * @return string
     * @throws Exception
     */
    protected function translateOrderType(string $orderType): string
    {
        switch ($orderType) {
            case 'LIMIT':
                return ORDER_TYPE_LIMIT;
            case 'MARKET':
                return ORDER_TYPE_MARKET;
            case 'STOP_LOSS':
                return ORDER_TYPE_STOP_LOSS;
            case 'STOP_LOSS_LIMIT':
                return ORDER_TYPE_STOP_LOSS_LIMIT;
            case 'TAKE_PROFIT':
                return ORDER_TYPE_TAKE_PROFIT;
            case 'TAKE_PROFIT_LIMIT':
                return ORDER_TYPE_TAKE_PROFIT_LIMIT;
            case 'LIMIT_MAKER':
                return ORDER_TYPE_LIMIT_MAKER;
            default:
                throw new Exception(sprintf('Unknown OrderType %s', $orderType));
        }
    }
}