<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/Modules/init.php";
use Srvclick\Magma4telegram\Magma;
use Modules\info;

Magma::$magma = [
    info::class
];

try {
    $botToken = 'Telegram BOT TOKEN';
    $magma = new Magma($botToken);

}catch (Exception $exception){
    echo $exception->getMessage();
}
