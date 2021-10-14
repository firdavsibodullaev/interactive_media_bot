<?php

namespace App\Services\TelegramServices;

use App\Constants\LanguageConstant;
use Illuminate\Http\Client\RequestException;

/**
 * Class RegisterService
 * @package App\Services\TelegramServices
 */
class RegisterService extends TelegramService
{
    /**
     * @throws RequestException
     */
    public function index()
    {
        $this->setMainAction();

        $this->switchActions();

    }

    /**
     * @throws RequestException
     */
    private function switchActions()
    {
        switch ($this->action->sub_action) {
            case SubactionsService::REGISTER_GET_LANGUAGE:
                $this->getLanguagesList();
                break;
            case SubactionsService::REGISTER_ENTER_NAME:
                $this->getLanguage();
                break;
            case SubactionsService::REGISTER_SEND_PHONE:
                $this->getName();
                break;
            case SubactionsService::REGISTER_CREATE:
                $this->getPhone();
                break;
        }
    }

    /**
     * @throws RequestException
     */
    private function getLanguagesList()
    {
        $this->action->sub_action = SubactionsService::REGISTER_ENTER_NAME;
        $this->action->save();
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Tilni tanlang'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::languagesButtons(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);
    }

    /**
     * @throws RequestException
     */
    private function getLanguage(): void
    {
        if (!in_array($this->text, LanguageConstant::translateList())) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __('To\'g\'ri tilni tanlang')
            ]);
            return;
        }

        $this->bot_user->lang = LanguageConstant::keyList($this->text);
        $this->bot_user->save();
        app()->setLocale($this->text);

        $this->action->sub_action = SubactionsService::REGISTER_SEND_PHONE;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Ismingizni kiriting')
        ]);
    }

    private function getName()
    {
        if (!ValidationService::validateName($this->text)) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __('Ismingizni to\'g\'ri kiriting')
            ]);
            return;
        }

        $this->bot_user->first_name = $this->text;
        $this->bot_user->save();

        $this->action->sub_action = SubactionsService::REGISTER_CREATE;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Telefon nomeringizni kiriting'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::phoneNumberButton(),
                'resize_keyboard' => true
            ])
        ]);
    }

    private function getPhone()
    {
        if (!ValidationService::validatePhone($this->updates->getContact(), $this->updates->isContact())) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __("\"Telefon raqam bilan ulashish\" tugmasini bosing")
            ]);
            return;
        }

        $this->bot_user->phone = $this->updates->getContact();
        $this->bot_user->status = true;
        $this->bot_user->save();

        $this->sendMainMenu();
    }

    private function setMainAction()
    {
        if ($this->action->action !== ActionsService::REGISTER || $this->text === '/start') {
            $this->action->action = ActionsService::REGISTER;
            $this->action->sub_action = SubactionsService::REGISTER_GET_LANGUAGE;
            $this->action->save();
        }
    }
}
