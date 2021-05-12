<?php

require __DIR__ . "/../vendor/autoload.php";

# 0BLJSlW9zYf9w2cm82FUB6QqqjVIZKMRcaPVLRt89o7F3xYyt3U6Q7oh8f3dPdkq
# EMEQDykEXp6jn8IHdhsMTA4YAWOON4ydCJcaEQi7rKcL00wt7MA8vMfVrZR7SwcC
$client = new \ExchangeApi\Exchange\Binance\BinanceSpot('0BLJSlW9zYf9w2cm82FUB6QqqjVIZKMRcaPVLRt89o7F3xYyt3U6Q7oh8f3dPdkq', 'EMEQDykEXp6jn8IHdhsMTA4YAWOON4ydCJcaEQi7rKcL00wt7MA8vMfVrZR7SwcC');



$result = $client->getOrder( '23212121', 'DOGEBTC');

var_dump(ORDER_STATUS_NEW);

//foreach($result as $spotResult) {
//    print_r($spotResult);
//}

