<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/Modules/init.php";


use Srvclick\Magma4telegram\Magma;
use Modules\info;
use Modules\buttons;

Magma::$magma = [
    info::class,
    buttons::class
];

try {
    $botToken = '';
    $magma = new Magma($botToken);

}catch (Exception $exception){
    echo $exception->getMessage();
}
