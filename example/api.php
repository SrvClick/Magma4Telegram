<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/Modules/init.php";
use Srvclick\Magma4telegram\Magma;
use Modules\info;

Magma::$magma = [
    info::class
];

try {

    $magma = new Magma();
}catch (Exception $exception){
    echo $exception->getMessage()."\n";
}
