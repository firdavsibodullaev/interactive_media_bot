<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    /**
     * @var Api
     */
    private $telegram;
    /**
     * @var WebhookUpdates
     */
    private $updates;
    /**
     * @var string
     */
    private $text;
    /**
     * @var int
     */
    private $chat_id;

    public function __construct()
    {
        $this->telegram = new Api();
        $this->updates = $this->telegram->getWebhookUpdates();
        $this->text = $this->updates->getText();
        $this->chat_id = $this->updates->getFromId();
    }

    public function index()
    {

    }

}
