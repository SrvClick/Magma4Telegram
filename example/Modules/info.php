<?php
namespace Modules;
use Srvclick\Magma4telegram\MagmaCommand;
use Exception;

use Srvclick\Magma4telegram\MagmaSend;
class info extends MagmaCommand{
    use MagmaSend;
    protected string $command = "/info {name}";
    protected ?string $chatId = null;
    public function handle(): void
    {
        try {
            $response = $this->SendTelegramMessage("Hello ".$this->argument('name'), $this->chatId);
            $response->verbose();
        } catch (Exception $e) {
            echo $e->getMessage()."\n";
        }
    }
}