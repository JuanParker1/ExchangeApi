<?php

namespace ExchangeApi\Exchange;

use Exception;
use GuzzleHttp\Client;

abstract class AbstractExchange
{
    protected Client $client;
    protected string $apiPublic;
    protected string $apiSecret;

    public function __construct(string $apiPublic = null, string $apiSecret = null)
    {
        define('POEP', 'ok');
        $this->client = new Client();
        $this->setApiKeys($apiPublic, $apiSecret);
        $this->defineConstants();
    }

    private function setApiKeys(?string $apiPublic, ?string $apiSecret): void
    {
        if (!is_null($apiPublic) && !is_null($apiSecret)) {
            $this->apiPublic = $apiPublic;
            $this->apiSecret = $apiSecret;
            return;
        }

        if (!empty($_ENV['EXCHANGE_API_PUBLIC']) && !empty($_ENV['EXCHANGE_API_SECRET'])) {
            $this->apiPublic = $_ENV['EXCHANGE_API_PUBLIC'];
            $this->apiSecret = $_ENV['EXCHANGE_API_SECRET'];
            return;
        }

        throw new Exception(sprintf('No API keys found.'));
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
    }

    protected function getMicroTime(): int
    {
        return (int)round(microtime(true) * 1000);
    }
}