<?php

namespace App\Services\TelegramServices;

use App\Constants\UserRoleConstant;
use App\Models\Action;
use App\Models\BotUser;
use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use Illuminate\Http\Client\RequestException;

/**
 * Class TelegramService
 * @package App\Services\TelegramServices
 */
class TelegramService
{
    /**
     * @var WebhookUpdates
     */
    public $updates;
    /**
     * @var Api
     */
    public $telegram;
    /**
     * @var int
     */
    public $chat_id;
    /**
     * @var string
     */
    public $text;
    /**
     * @var BotUser
     */
    public $bot_user;
    /**
     * @var Action
     */
    public $action;

    public function __construct(Api $telegram, WebhookUpdates $updates)
    {
        $this->telegram = $telegram;
        $this->updates = $updates;

        $this->chat_id = $updates->getFromId();
        $this->text = $updates->getText();
        $this->bot_user = $this->getBotUser();
        $this->action = $this->getAction();
        $this->setLanguage();
    }

    /**
     * @throws RequestException
     */
    public function index()
    {
        if ($this->bot_user->role === UserRoleConstant::ADMIN) {
            (new AdminActionService($this->telegram, $this->updates))->index();
        } else {
            (new ClientActionService($this->telegram, $this->updates))->index();
        }
    }

    /**
     * @return BotUser
     */
    private function getBotUser(): BotUser
    {
        /** @var BotUser $bot_user */
        $bot_user = BotUser::query()->firstOrCreate([
            'chat_id' => $this->chat_id
        ], [
            'first_name' => $this->updates->getFirstName(),
            'username' => $this->updates->getUsername(),
        ]);

        return $bot_user;
    }

    private function setLanguage()
    {
        app()->setLocale($this->bot_user->lang);
    }

    /**
     * @return Action
     */
    private function getAction(): Action
    {
        /** @var Action $action */
        $action = Action::query()->firstOrCreate(['chat_id' => $this->chat_id], [
            'action' => null,
            'sub_action' => null
        ]);

        return $action;
    }

    public function setActionNull()
    {
        $this->action->action = null;
        $this->action->sub_action = null;
        $this->action->save();
    }

    public function sendMainMenu()
    {
        return $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Bo\'limni tanlang'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::mainButtons(),
                'resize_keyboard' => true,
            ])
        ]);
    }
}
