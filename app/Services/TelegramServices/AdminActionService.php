<?php

namespace App\Services\TelegramServices;

use App\Constants\UserRoleConstant;
use Illuminate\Http\Client\RequestException;

/**
 * Class AdminActionService
 * @package App\Services
 */
class AdminActionService extends TelegramService
{
    public function index()
    {
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
            $this->sendMainMenu(UserRoleConstant::ADMIN);
        }

    }
}
