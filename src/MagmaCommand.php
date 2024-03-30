<?php
namespace Srvclick\Magma4telegram;
use Exception;

class MagmaCommand{
    public array $params = [];
    public function setArguments($argument): void
    {
        $this->params = $argument;
    }

    /**
     * @throws Exception
     */
    public function argument($argument){
        if (!isset($this->params[$argument])) throw new Exception('Este argumento no exist');
        return $this->params[$argument];
    }
}
