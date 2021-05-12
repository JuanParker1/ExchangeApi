<?php

namespace ExchangeApi\Exchange\Binance;

use Exception;
use ExchangeApi\Exchange\AbstractExchange;
use GuzzleHttp\Exception\GuzzleException;

abstract class AbstractBinance extends AbstractExchange
{
    public function __construct(string $apiPublic = null, string $apiSecret = null)
    {
        parent::__construct($apiPublic, $apiSecret);
    }

//    protected function post(): array
//    {
//        $headers = [];
//        $time = (int)round(microtime(true) * 1000);
//        $queryString = 'timestamp=' . $time . '&recvWindow=60000';
//        $hash = hash_hmac('sha256', $queryString, $this->apiSecret);
//        $queryString .= '&signature=' . $hash;
//        $headers['headers'] = [
//            'X-MBX-APIKEY' => $this->apiPublic,
//            'signature' => $hash
//        ];
//
//        $url = 'https://fapi.binance.com/fapi/v2/balance' . '?' . $queryString;
//
//        $response = $this->client->request('GET', $url, $headers);
//        $result = json_decode($response->getBody()->getContents(), true);
//    }

    /**
     * @param string $uri
     * @param string|null $queryParams
     * @return array
     * @throws GuzzleException
     */
    protected function get(string $uri, string $queryParams = null): array
    {
        $queryString = 'timestamp=' . $this->getMicroTime() . '&recvWindow=60000';
        if (!is_null($queryParams)) $queryString = $queryParams . '&' . $queryString;

        $hash = hash_hmac('sha256', $queryString, $this->apiSecret);
        $queryString .= '&signature=' . $hash;
        $headers['headers'] = [
            'X-MBX-APIKEY' => $this->apiPublic,
            'signature' => $hash
        ];

        $url = $uri . '?' . $queryString;

        $response = $this->client->request('GET', $url, $headers);

        return json_decode($response->getBody()->getContents(), true);
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