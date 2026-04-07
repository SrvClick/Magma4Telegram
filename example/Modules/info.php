<?php
namespace Modules;

use Srvclick\Magma4telegram\MagmaCommand;
use Srvclick\Magma4telegram\MagmaSend;
use Exception;

class info extends MagmaCommand {
    use MagmaSend;

    protected string $command = "/info {name}";
    protected ?string $chatId = null;

    public function handle(): void
    {
        try {
            $this->sendTelegramMessage($this->chatId, "Hello " . $this->argument('name'));
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}