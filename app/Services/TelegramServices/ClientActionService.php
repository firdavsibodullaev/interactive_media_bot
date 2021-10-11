<?php

namespace App\Services\TelegramServices;

use App\Models\Message;
use Illuminate\Http\Client\RequestException;

/**
 * Class ClientActionService
 * @package App\Services
 */
class ClientActionService extends TelegramService
{
    /**
     * @throws RequestException
     */
    public function index()
    {
        if (!$this->bot_user->status) {
            $this->register();
            return;
        }

        $this->deleteMessage();

        $this->start();

        $this->actions();
    }

    /**
     * @throws RequestException
     */
    public function start()
    {
        if ($this->text === '/start') {
            $this->sendMainMenu();
        }
    }

    /**
     * @throws RequestException
     */
    public function register()
    {
        if (!$this->bot_user->status) {
            (new RegisterService($this->telegram, $this->updates))->index();
        }
    }
}
