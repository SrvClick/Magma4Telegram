<?php
namespace Srvclick\Magma4telegram;

use ReflectionException;
use Exception;
use ReflectionClass;

/*
 * @project: Magma for Telegram
 * @developer: zXero
 * @copyright: All copyright reserved
 * @contact: https://www.srvclick.com
 * @version: 1.0 BETA - 30/03/2024
 */
class Magma extends MagmaKernel{

    /**
     * @throws Exception
     */
    public function __construct($command){
        if (!isset($command) or empty($command)) throw new Exception("Invalid Command");
        $command = $this->parser($command);
        $name = $command['command'];
        $arg = [];
       foreach (self::$magma as $class){
           try {
           $magma = new ReflectionClass($class);
           $classCommand = $magma->getProperty('command')->getDefaultValue();
           $userCommand = $this->parser($classCommand);

           if ($name == $userCommand['command']){
               if (count($userCommand['args']) > 0){
                   if (count($userCommand['args']) != count($command['args'])) throw new Exception('Cantidad de parametros invalidos');
                   for ($i = 0; $i < count($userCommand['args']); $i++){
                       $arg[$userCommand['args'][$i]] = $command['args'][$i];
                   }
               }
               $app = new $magma->name;
               $app->setArguments( $arg );
               $app->handle();
               break;
             }
           } catch (ReflectionException $e) {
               echo $e->getMessage();
           }
       }

    }
    public function parser($command): array
    {
        $parser = explode(" ",$command);
        $args = [];
        foreach ($parser as $arg){
            $tmparg = str_replace(['{','}'],null,$arg);
            if ($tmparg == $parser[0]) continue;
            $args[] = $tmparg;
        }
        $command = $parser[0];
        return compact('command','args');
    }
}
