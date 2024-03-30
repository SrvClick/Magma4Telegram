<?php
namespace Modules;
use Srvclick\Magma4telegram\MagmaCommand;
use Exception;
class info extends MagmaCommand{
    protected string $command = "/info {me}";
    public function handle(): void
    {
        echo $this->argument('me');
    }


}