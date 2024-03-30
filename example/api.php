<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/Modules/init.php";
use Srvclick\Magma4telegram\Magma;
use Modules\info;
Magma::$magma = [
    info::class
];

try {
    $update = json_decode(file_get_contents('php://input'),true);
    if (!isset($update['message']['text'])) throw new Exception('No se localizo el comando');
    $command = $update['message']['text'];
    $magma = new Magma($command);
}catch (Exception $exception){
    echo $exception->getMessage()."\n";
}
