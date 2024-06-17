<?php
namespace Srvclick\Magma4telegram;
use Srvclick\Scurl\Scurl_Request;
use Exception;

trait MagmaSend{

    private ?string $telegramBotUrl = null;
    public function MagmaSetBotToken($token): void
    {
        $this->telegramBotUrl = "https://api.telegram.org/bot".$token;
    }
    /**
     * @throws Exception
     */
    public function SendTelegramMessage(?string $message = null, ?string $chatId = null): \Srvclick\Scurl\Response
    {
        if (empty($message) || empty($chatId)) throw new Exception('Missing message or telegram chatId');
        if (empty($this->telegramBotUrl)) throw new Exception('Missing Telegram token');
        $curl = new Scurl_Request();
        $curl->setUrl($this->telegramBotUrl."/sendmessage?chat_id=".$chatId."&text=".urlencode($message)."&parse_mode=html" );
        return $curl->Send();
    }

}
