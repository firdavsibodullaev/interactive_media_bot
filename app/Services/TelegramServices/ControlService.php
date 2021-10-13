<?php

namespace App\Services\TelegramServices;

use App\Constants\ControlActionsConstant;
use App\Constants\UserRoleConstant;
use App\Services\ActionOneService;
use Illuminate\Http\Client\RequestException;

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
        $this->setControlMainAction1();
    }

    /**
     * @throws RequestException
     */
    private function sendIndex()
    {
        if ($this->action->action == null)
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
            $this->action->action_1 = null;
            $this->action->sub_action = null;
            $this->action->save();
        }
    }

    /**
     * @throws RequestException
     */
    public function setControlMainAction1()
    {
        if (!$this->action->action_1) {
            switch (ControlActionsConstant::getKey($this->text)) {
                case ControlActionsConstant::BACK:
                    $this->sendMainMenu(UserRoleConstant::ADMIN);
                    break;
                case ControlActionsConstant::EDIT_CONTACT:
                    (new ContactSettingsService($this->telegram, $this->updates))->index();
                    break;
            }
            return;
        }


        switch ($this->action->action_1) {
            case ActionOneService::CONTACT_EDIT:
                (new ContactSettingsService($this->telegram, $this->updates))->index();
                break;
        }
    }
}
