<?php

namespace App\Services\TelegramServices;

use App\Models\Action;
use App\Models\Appeal;
use App\Models\BotUser;
use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\CommandsService;
use App\Services\TelegramServices\Telegram\TelegramSettings;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use Illuminate\Http\Client\RequestException;

/**
 * Class BotActionService
 * @package App\Services\TelegramServices
 */
class BotActionService
{

    /**
     * @var Api
     */
    protected $telegram;
    /**
     * @var WebhookUpdates
     */
    protected $updates;
    /**
     * @var int
     */
    protected $chatId;
    /**
     * @var int
     */
    protected $fromId;
    /**
     * @var string
     */
    protected $text;
    /**
     * @var BotUser
     */
    protected $botUser;
    /**
     * @var Action
     */
    protected $action;

    public function __construct(Api $telegram, WebhookUpdates $updates)
    {
        $this->telegram = $telegram;
        $this->updates = $updates;
        $this->chatId = $this->updates->getChatId();
        $this->fromId = $this->updates->getFromId();
        $this->text = $this->updates->getText();
        $this->botUser = BotUser::query()
            ->where('ChatId', '=', $this->fromId)
            ->where('Status', '=', ConstantsService::STATUS_ACTIVE)
            ->first();
        $this->action = $this->getAction();
    }

    /**
     * @throws RequestException
     */
    public function index()
    {
        $this->chatMember();
        if (!$this->updates->isGroup()) {
            $this->editMessage();

            $this->checkUser();
            $this->command();

            $this->appeals();
        }
    }

    /**
     * Проверка статуса регистрации пользователя в боте
     *
     * @throws RequestException
     */
    private function checkUser()
    {
        if (
            !$this->botUser
            && !$this->updates->isCommand()
            && !TelegramSettings::isAdmin($this->fromId)
        ) {
            $bot_user = new BotUserService($this->telegram, $this->updates);
            $bot_user->index();
            die();
        }
    }

    /**
     * Проверка статуса пользователя
     */
    private function chatMember()
    {
        if ($this->updates->myChatMember()) {
            $this->botUser->Status = ($this->updates->myChatMemberStatus() === "member")
                ? ConstantsService::STATUS_ACTIVE
                : ConstantsService::STATUS_PASSIVE;
            $this->botUser->save();
            die();
        }
    }

    /**
     * Команды
     *
     * @throws RequestException
     */
    private function command()
    {
        if ($this->updates->isCommand()) {
            $command = new CommandsService($this->telegram, $this->updates);
            $command->index(TelegramSettings::isAdmin($this->fromId));
        }
    }

    /**
     * @param string|null $text
     * @throws RequestException
     */
    protected function sendMainMenu(?string $text = null)
    {
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => $text ?? __('Меню'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::mainButtons(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);
    }

    /**
     * @throws RequestException
     */
    private function appeals()
    {
        $appeals = new AppealsService($this->telegram, $this->updates);
        $appeals->index();
    }

    private function getAction()
    {
        return Action::query()
            ->where('ChatId', '=', $this->fromId)
            ->firstOrCreate(['ChatId' => $this->fromId], [
                'Action' => null,
                'SubAction' => null
            ]);
    }

    public function editMessage()
    {
        if ($this->updates->isEditedMessage()) {
            /** @var Appeal $appeal */
            $appeal = Appeal::query()->where('MessageId', '=', $this->updates->getMessageId())->first();
            $appeal->Text = $this->text;
            $appeal->save();
            die();
        }
    }
}
