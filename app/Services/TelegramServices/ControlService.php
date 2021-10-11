<?php

namespace App\Services\TelegramServices;

use App\Services\ActionOneService;

/**
 * Class ControlService
 * @package App\Services\TelegramServices
 */
class ControlService extends TelegramService
{
    public function index()
    {

        $this->sendIndex();
        $this->setMainAction();
    }

    private function sendIndex()
    {
        if ($this->action->action)
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('O\'zgarish tugmasini bosing'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::controlSettingsButtons(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    public function setMainAction()
    {
        if ($this->action->action !== ActionsService::BOT_CONTROL) {
            $this->action->action = ActionsService::BOT_CONTROL;
            $this->action->action_1 = ActionOneService::CONTACT_EDIT;
            $this->action->sub_action = null;
            $this->action->save();
        }
    }
}
