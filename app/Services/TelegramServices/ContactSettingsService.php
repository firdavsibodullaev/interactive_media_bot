<?php

namespace App\Services\TelegramServices;

use App\Constants\ControlActionsConstant;
use App\Models\Contact;
use App\Services\ActionOneService;
use Illuminate\Http\Client\RequestException;

/**
 * Class ContactSettingsService
 * @package App\Services\TelegramServices
 */
class ContactSettingsService extends TelegramService
{
    public function index()
    {
        $this->setMainAction();

        $this->switchActions();

    }

    /**
     * @throws RequestException
     */
    public function switchActions()
    {
        switch ($this->action->sub_action) {
            case SubactionsService::GET_MAIN_BUTTONS:
                $this->getButtons();
                break;
            case SubactionsService::GET_CONTACT_EDIT_ACTION:
                $this->getEditAction();
                break;
            case SubactionsService::EDIT_CONTACT_GET_INSTAGRAM:
            case SubactionsService::EDIT_CONTACT_GET_TELEGRAM:
            case SubactionsService::EDIT_CONTACT_GET_PHONE:
            case SubactionsService::EDIT_CONTACT_GET_LOCATION:
                $this->getAction();
                break;
        }
    }

    /**
     * @throws RequestException
     */
    public function getButtons()
    {
        $this->action->sub_action = SubactionsService::GET_CONTACT_EDIT_ACTION;
        $this->action->save();
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __("Bo'limni tanlang"),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::sendContactEditButtons(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getEditAction()
    {
        if ($this->text === __(ControlActionsConstant::BACK)) {
            $this->controlMainMenu();
            return;
        }
        switch (ControlActionsConstant::getKey($this->text)) {
            case ControlActionsConstant::EDIT_INSTAGRAM:
                $this->editInstagram();
                break;
            case ControlActionsConstant::EDIT_TELEGRAM:
                $this->editTelegram();
                break;
            case ControlActionsConstant::EDIT_PHONE:
                $this->editPhone();
                break;
            case ControlActionsConstant::EDIT_LOCATION:
                $this->editLocation();
                break;
            default:
        }

    }

    /**
     * @throws RequestException
     */
    public function editInstagram()
    {
        $this->action->sub_action = SubactionsService::EDIT_CONTACT_GET_INSTAGRAM;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Instagram akkauntingizni kiriting'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::returnBackButton(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @throws RequestException
     */
    public function editTelegram()
    {
        $this->action->sub_action = SubactionsService::EDIT_CONTACT_GET_TELEGRAM;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Telegram akkauntingizni kiriting'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::returnBackButton(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @throws RequestException
     */
    public function editLocation()
    {
        $this->action->sub_action = SubactionsService::EDIT_CONTACT_GET_LOCATION;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Lokasiyani jo\'nating'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::returnBackButton(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @throws RequestException
     */
    public function editPhone()
    {
        $this->action->sub_action = SubactionsService::EDIT_CONTACT_GET_PHONE;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Telefon raqamingizni kiriting akkauntingizni kiriting'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::phoneNumberOrBackButton(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getAction()
    {
        if ($this->text === ControlActionsConstant::BACK) {
            $this->getButtons();
            return;
        }
        $contact = $this->getContact();
        $value = $this->text;

        if ($contact === 'location') {

            if (!$this->updates->isLocation()) {
                return $this->telegram->send('sendMessage', [
                    'chat_id' => $this->chat_id,
                    'text' => __('Lokatsiya jo\'nating')
                ]);
            }

            $value = $this->updates->getLocation();
        } elseif ($contact === 'phone') {
            if (!ValidationService::validatePhone($this->updates->getContact(), $this->updates->isContact())) {
                $this->telegram->send('sendMessage', [
                    'chat_id' => $this->chat_id,
                    'text' => __("\"Telefon raqam bilan ulashish\" tugmasini bosing")
                ]);
                return;
            }
            $value = $this->updates->getContact();
        }

        Contact::query()->where('name', '=', $contact)->update([
            'value' => $value
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Ma\'lumotlar o\'zgartirildi')
        ]);

        $this->getButtons();
    }

    public function setMainAction()
    {
        if ($this->action->action_1 !== ActionOneService::CONTACT_EDIT) {
            $this->action->action_1 = ActionOneService::CONTACT_EDIT;
            $this->action->sub_action = SubactionsService::GET_MAIN_BUTTONS;
            $this->action->save();
        }
    }

    /**
     * @throws RequestException
     */
    public function controlMainMenu()
    {
        $this->action->action_1 = null;
        $this->action->sub_action = null;
        $this->action->save();
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('O\'zgarish tugmasini bosing'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::controlSettingsButtons(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @return string
     */
    private function getContact(): string
    {
        $array = explode('.', $this->action->sub_action);
        return end($array);
    }
}
