<?php

namespace App\Services\TelegramServices;

use App\Constants\UserRoleConstant;
use App\Models\Action;
use App\Models\BotUser;
use App\Models\Message;
use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use App\ServiceTrait;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

/**
 * Class TelegramService
 * @package App\Services\TelegramServices
 */
class TelegramService
{
    use ServiceTrait;

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
        if ($this->updates->myChatMember()) {
            $this->checkChatMember();
            return;
        }
        if ($this->bot_user->role === UserRoleConstant::ADMIN) {
            (new AdminActionService($this->telegram, $this->updates))->index();
        } else {
            (new ClientActionService($this->telegram, $this->updates))->index();
        }
    }

    /**
     * @return BotUser
     */
    protected function getBotUser(): BotUser
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
        $this->action->action_1 = null;
        $this->action->sub_action = null;
        $this->action->save();
    }

    /**
     * @return array|Response
     * @throws RequestException
     */
    public function sendMainMenu()
    {
        $role = $this->bot_user->role;
        $this->setActionNull();
        return $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Bo\'limni tanlang'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::mainButtons($role),
                'resize_keyboard' => true,
            ])
        ]);
    }

    public function saveMessage($message)
    {
        $now = now();
        $array = [
            [
                'message_id' => $message['result']['message_id'],
                'chat_id' => $this->chat_id,
                'created_at' => $now,
            ],
        ];
        if ($message_id = $this->updates->getMessageId()) {
            array_push($array,
                [
                    'message_id' => $message_id,
                    'chat_id' => $this->chat_id,
                    'created_at' => $now,
                ]);
        }

        Message::query()->insert($array);
    }


    /**
     * @throws RequestException
     */
    public function deleteMessage()
    {
        if ($this->updates->callbackQuery()) {
            return;
        }
        $messages = Message::query()->where('chat_id', '=', $this->chat_id)->get();
        foreach ($messages as $message) {
            if (time() - $message->created_at->timestamp < 60 * 60 * 24 * 2) {
                $this->telegram->send('deleteMessage', [
                    'chat_id' => $this->chat_id,
                    'message_id' => $message->message_id
                ]);
            } else {
                $this->telegram->send('editMessageReplyMarkup', [
                    'chat_id' => $this->chat_id,
                    'message_id' => $message->message_id
                ], true);
            }
            $message->delete();
        }
    }

    /**
     * @throws RequestException
     */
    private function checkChatMember()
    {
        if ($this->updates->myChatMemberStatus() !== 'member') {
            return;
        }
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __("Qaytganingizdan xo'rsandmiz")
        ]);

    }
}
