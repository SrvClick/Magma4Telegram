<?php
namespace Srvclick\Magma4telegram;
use Exception;
/*
 * @project: Magma for Telegram
 * @developer: zXero
 * @copyright: All copyright reserved
 * @contact: https://www.srvclick.com
 * @version: 1.0.1 BETA - 30/03/2024
 */
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
        if (!isset($this->params[$argument])) throw new Exception('Este argumento no existe');
        return $this->params[$argument];
    }
}
