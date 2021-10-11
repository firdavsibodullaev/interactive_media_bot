<?php

namespace App\Services\TelegramServices;

use Illuminate\Http\Client\RequestException;

/**
 * Class AdminActionService
 * @package App\Services
 */
class AdminActionService extends TelegramService
{
    public function index()
    {
        $this->start();
    }

    /**
     * @throws RequestException
     */
    public function start()
    {
        if ($this->text === '/start') {
            $this->sendMainMenu('role');
        }

        $this->actions();
    }
}
