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
 * @version: 1.0.1 BETA - 30/03/2024
 */
class Magma extends MagmaKernel {
    private static array $reflectionCache = [];

    /**
     * @throws Exception
     */
    public function __construct(string $command, ?string $chatId = null) {
        if (empty($command)) throw new Exception("Comando inválido: el comando no puede estar vacío");
        $commandData = $this->parseCommand($command);
        if (!isset($commandData['commandName'])) throw new Exception("Comando inválido: no se pudo extraer el nombre del comando");
        if (!isset($commandData['args'])) throw new Exception("Comando inválido: no se pudieron extraer los argumentos");
        $commandName = $commandData['commandName'];
        $arguments = $commandData['args'];
        foreach (self::$magma as $class) {
            try {
                $reflection = $this->getCachedReflection($class);
                $classCommand = $reflection->getProperty('command')->getDefaultValue();
                $userCommand = $this->parseCommand($classCommand);

                if ($commandName == $userCommand['commandName']) {
                    $this->validateArguments($arguments, $userCommand['args']);
                    foreach ($userCommand['args'] as $index => $argName) {
                        $arguments[$argName] = $arguments[$index];
                    }
                    $app = $reflection->newInstance();
                    $app->setArguments($arguments);
                    $reflection->getProperty('chatId')->setValue($app, $chatId);
                    $app->handle();
                    return;
                }
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
        throw new Exception("Comando no reconocido: $commandName");
    }


    /**
     * @throws ReflectionException
     */
    private function getCachedReflection(string $class): ReflectionClass {
        if (!isset(self::$reflectionCache[$class])) self::$reflectionCache[$class] = new ReflectionClass($class);
        return self::$reflectionCache[$class];
    }

    /**
     * @throws Exception
     */
    private function validateArguments(array $providedArgs, array $expectedArgs): void {
        if (count($providedArgs) != count($expectedArgs)) throw new Exception('Número de parámetros inválido');
    }

    private function parseCommand(string $command): array {
        $parts = explode(" ", $command);
        $commandName = array_shift($parts);
        $args = array_map(function($arg) {
            return trim($arg, '{}');
        }, $parts);
        return compact('commandName', 'args');
    }
}


