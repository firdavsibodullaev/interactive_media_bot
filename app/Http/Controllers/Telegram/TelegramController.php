<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use App\Services\TelegramServices\TelegramService;
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

    public function __construct()
    {
        $this->telegram = new Api();
        $this->updates = $this->telegram->getWebhookUpdates();
    }

    public function index()
    {
        $telegram = new TelegramService($this->telegram, $this->updates);
        $telegram->index();
    }

}
