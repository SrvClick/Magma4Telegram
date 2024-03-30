<?php

namespace Srvclick\Magma4telegram;

use Exception;
class Api{
    public function api(){
        try {
            $update = json_decode(file_get_contents('php://input'),true);
            if (!isset($update['message']['text'])) throw new Exception('No se localizo el comando');
            $command = $update['message']['text'];
            $magma = new Magma($command);
        }catch (Exception $exception){
            echo $exception->getMessage()."\n";
        }

    }

}
