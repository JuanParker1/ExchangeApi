<?php

namespace ExchangeApi;

use Exception;
use ExchangeApi\Exchange\Binance\AbstractBinance;
use ExchangeApi\Exchange\Binance\BinanceSpot;
use ExchangeApi\Exchange\ExchangeSpotInterface;

class ExchangeApiFactory
{
    /**
     * @param string|null $exchangeName
     * @param string|null $apiPublic
     * @param string|null $apiSecret
     * @return ExchangeSpotInterface
     * @throws Exception
     */
    static function getExchangeSpotClient(?string $exchangeName = null, ?string $apiPublic = null, ?string $apiSecret = null): ExchangeSpotInterface
    {
        $exchangeName = $exchangeName ?? $_ENV['EXCHANGE_NAME'];
        $apiPublic = $apiPublic ?? $_ENV['EXCHANGE_API_KEY_PUBLIC'];
        $apiSecret = $apiSecret ?? $_ENV['EXCHANGE_API_KEY_SECRET'];

        if (is_null($apiSecret) || is_null($apiPublic) || is_null($exchangeName)) {
            throw new Exception(sprintf('Missing arguments. $exchangeName (%s), $keyPublic (%s), $keySecret (%s)',
                $exchangeName, $apiPublic, $apiSecret));
        }

        switch (strtolower($exchangeName)) {
            case strtolower(AbstractBinance::EXCHANGE_NAME):
                return new BinanceSpot($apiPublic, $apiSecret);
            default:
                throw new Exception(sprintf('No exchange implementation found for: %s', $exchangeName));
        }

    }

}