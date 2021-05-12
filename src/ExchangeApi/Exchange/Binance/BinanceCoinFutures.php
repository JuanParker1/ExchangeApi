<?php

namespace ExchangeApi\Exchange\Binance;

use ExchangeApi\Exchange\ExchangeCoinFuturesInterface;

final class BinanceCoinFutures extends AbstractBinance implements ExchangeCoinFuturesInterface
{
public function __construct(string $apiPublic = null, string $apiSecret = null)
{


    parent::__construct($apiPublic, $apiSecret);
}
}