<?php

namespace ExchangeApi\Exchange;

use GuzzleHttp\Client;

abstract class AbstractExchange
{
    const DELETE = 'DELETE';
    const POST = 'POST';
    const GET = 'GET';

    protected Client $client;
    protected string $apiPublic;
    protected string $apiSecret;

    /**
     * AbstractExchange constructor.
     * @param string $apiPublic
     * @param string $apiSecret
     */
    public function __construct(string $apiPublic, string $apiSecret)
    {
        $this->client = new Client();
        $this->apiPublic = $apiPublic;
        $this->apiSecret = $apiSecret;

        $this->defineConstants();
    }

    private function defineConstants(): void
    {
        // status
        define('ORDER_STATUS_NEW', 'NEW');
        define('ORDER_STATUS_PARTIALLY_FILLED', 'PARTIALLY_FILLED');
        define('ORDER_STATUS_FILLED', 'FILLED');
        define('ORDER_STATUS_CANCELED', 'CANCELED');
        define('ORDER_STATUS_REJECTED', 'REJECTED');
        define('ORDER_STATUS_EXPIRED', 'EXPIRED');

        // side
        define('ORDER_SIDE_BUY', 'BUY');
        define('ORDER_SIDE_SELL', 'SELL');

        // type
        define('ORDER_TYPE_LIMIT', 'LIMIT');
        define('ORDER_TYPE_MARKET', 'MARKET');
        define('ORDER_TYPE_STOP_LOSS', 'STOP_LOSS');
        define('ORDER_TYPE_STOP_LOSS_LIMIT', 'STOP_LOSS_LIMIT');
        define('ORDER_TYPE_TAKE_PROFIT', 'TAKE_PROFIT');
        define('ORDER_TYPE_TAKE_PROFIT_LIMIT', 'TAKE_PROFIT_LIMIT');
        define('ORDER_TYPE_LIMIT_MAKER', 'LIMIT_MAKER');
        define('ORDER_TIME_IN_FORCE_GTC', 'GTC');
    }

    protected function getMicroTime(): int
    {
        return (int)round(microtime(true) * 1000);
    }
}
