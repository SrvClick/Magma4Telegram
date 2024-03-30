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
class Magma extends MagmaKernel {
    private static array $reflection = [];

    /**
     * @throws Exception
     */
    public function __construct($command) {
        if (!isset($command) || empty($command)) {
            throw new Exception("Invalid Command");
        }

        $commandData = $this->parser($command);
        $name = $commandData['command'];
        $arg = [];

        foreach (self::$magma as $class) {
            try {
                $reflection = $this->getCachedReflection($class);
                $classCommand = $reflection->getProperty('command')->getDefaultValue();
                $userCommand = $this->parser($classCommand);

                if ($name == $userCommand['command']) {
                    $this->validateArguments($commandData['args'], $userCommand['args']);

                    foreach ($userCommand['args'] as $index => $argName) {
                        $arg[$argName] = $commandData['args'][$index];
                    }

                    $app = $reflection->newInstance();
                    $app->setArguments($arg);
                    $app->handle();
                    return;
                }
            } catch (ReflectionException $e) {
            }
        }
    }

    private function getCachedReflection($class) {
        if (!isset(self::$reflection[$class])) {
            self::$reflection[$class] = new ReflectionClass($class);
        }
        return self::$reflection[$class];
    }

    /**
     * @throws Exception
     */
    private function validateArguments($providedArgs, $expectedArgs): void
    {
        if (count($providedArgs) != count($expectedArgs)) {
            throw new Exception('Invalid number of parameters');
        }
    }

    public function parser($command): array {
        $parser = explode(" ", $command);
        $args = array_map(function($arg) {
            return trim($arg, '{}');
        }, array_slice($parser, 1));
        $command = $parser[0];
        return compact('command', 'args');
    }
}
