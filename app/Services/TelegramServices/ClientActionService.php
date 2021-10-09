<?php

namespace App\Services\TelegramServices;

use App\Constants\MainMenuConstant;
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

        $this->start();

        $this->actions();
    }

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

    /**
     * @throws RequestException
     */
    private function actions()
    {
        if ($this->isAction('contact')) {
            (new ContactService($this->telegram, $this->updates))->index();
        }
    }


    /**
     * @param string $action
     * @return bool
     */
    private function isAction(string $action): bool
    {
        return [
            'contact' => ($this->text === __(MainMenuConstant::CONTACT)),
            'video' => ($this->text === __(MainMenuConstant::VIDEO)),
            'photo' => ($this->text === __(MainMenuConstant::PHOTO)),
        ][$action];
    }
}
