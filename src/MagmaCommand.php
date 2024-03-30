<?php
namespace Srvclick\Magma4telegram;
use Exception;

class MagmaCommand{
    public array $params = [];
    public function setArguments($argument){
        $this->params = $argument;
    }
    public function argument($argument){
        if (!isset($this->params[$argument])) throw new Exception('Este argumento no existe');
        return $this->params[$argument];
    }
}
