<?php
namespace Srvclick\Magma4telegram;

use ReflectionException;
use Exception;
use ReflectionClass;

class Magma {
    private static array $reflectionCache = [];
    private ?string $botToken;

    public static array $magma = [];

    /**
     * @throws Exception
     */
    public function __construct(string $botToken) {
        $this->botToken = $botToken;
        $request = new TelegramRequest();
        $chatId = $request->getChatId();
        $messageId = $request->getMessageId();

        if (method_exists($request, 'isCallbackQuery') && $request->isCallbackQuery()) {
            $callbackData = $request->getCallbackData();

            foreach (self::$magma as $class) {
                try {
                    $reflection = $this->getCachedReflection($class);
                    $defaultProps = $reflection->getDefaultProperties();

                    if (isset($defaultProps['callbacks']) && isset($defaultProps['callbacks'][$callbackData])) {
                        $methodToCall = $defaultProps['callbacks'][$callbackData];
                        $app = $reflection->newInstance();
                        $chatIdProp = $reflection->getProperty('chatId');
                        $chatIdProp->setAccessible(true);
                        $chatIdProp->setValue($app, $chatId);
                        if ($reflection->hasProperty('incomingMessageId')) {
                            $msgIdProp = $reflection->getProperty('incomingMessageId');
                            $msgIdProp->setAccessible(true);
                            $msgIdProp->setValue($app, $messageId);
                        }
                        $app->MagmaSetBotToken($this->botToken);
                        if (method_exists($app, $methodToCall)) {
                            try {
                                $app->$methodToCall();
                            } catch (\Throwable $e) {
                                file_put_contents('error_log.txt', "Error: " . $e->getMessage());
                            }
                            return;
                        }
                    }
                } catch (ReflectionException $e) {
                    throw new Exception($e->getMessage());
                }
            }
            return;
        }

        $command = trim($request->getCommand());

        $commandName = preg_split('/\s+/', $command)[0];

        foreach (self::$magma as $class) {
            try {
                $reflection = $this->getCachedReflection($class);
                $classCommand = $reflection->getProperty('command')->getDefaultValue();
                $userCommand = $this->parseCommand($classCommand);
                if ($commandName === $userCommand['commandName']) {
                    $expectedArgsCount = count($userCommand['args']);
                    $limit = $expectedArgsCount > 0 ? $expectedArgsCount + 1 : 1;
                    $inputParts = preg_split('/\s+/', $command, $limit);
                    array_shift($inputParts);
                    $arguments = $inputParts;
                    $this->validateArguments($arguments, $userCommand['args']);
                    $mappedArguments = [];
                    foreach ($userCommand['args'] as $index => $argName) {
                        $mappedArguments[$argName] = $arguments[$index];
                    }
                    $app = $reflection->newInstance();
                    $app->setArguments($mappedArguments);
                    $reflection->getProperty('chatId')->setValue($app, $chatId);
                    $app->MagmaSetBotToken($this->botToken);
                    $app->handle();
                    return;
                }
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
        throw new Exception("Command not found");
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
        if (count($providedArgs) !== count($expectedArgs)) {
            throw new Exception('Invalid number of parameters');
        }
    }

    private function parseCommand(string $commandTemplate): array {
        $parts = preg_split('/\s+/', trim($commandTemplate));
        $commandName = array_shift($parts);
        $args = array_map(function($arg) {
            return trim($arg, '{}');
        }, $parts);
        return compact('commandName', 'args');
    }
}


