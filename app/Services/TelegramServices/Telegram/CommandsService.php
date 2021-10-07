<?php


namespace App\Services\TelegramServices\Telegram;

use App\Models\Action;
use App\Models\BotUser;
use App\Services\TelegramServices\BotActionService;
use App\Services\TelegramServices\BotUserService;
use App\Services\TelegramServices\KeyboardsService;
use Illuminate\Http\Client\RequestException;

/**
 * Class CommandsService
 * @package App\Services\TelegramServices\Telegram
 */
class CommandsService extends BotActionService
{

    /**
     * @param bool $isAdmin
     * @throws RequestException
     */
    public function index(bool $isAdmin = false)
    {
        switch ($this->text) {
            case '/start':
                $this->startCommand($isAdmin);
                break;
            default:
                $this->unsupportedCommand();
        }
    }

    /**
     * @param bool $isAdmin
     * @throws RequestException
     */
    private function startCommand(bool $isAdmin)
    {
        Action::query()->updateOrCreate([
            'ChatId' => $this->chatId
        ], [
            'Action' => null,
            'SubAction' => null,
        ]);

        $botUser = $this->getBotUser($isAdmin);

        if (TelegramSettings::isAdmin($this->chatId)) {
            $this->adminStart();
        } else {
            $this->userStart();
        }
    }

    /**
     * @throws RequestException
     */
    private function unsupportedCommand()
    {
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Неизвестная команда, введите /start")
        ]);
    }

    /**
     * Получает или создаёт пользователя телеграм бота
     * @param int $isAdmin
     * @return BotUser|null
     * @throws RequestException
     */
    private function getBotUser(int $isAdmin): ?BotUser
    {
        if (!$this->botUser && !TelegramSettings::isAdmin($this->chatId)) {
            $botUser = new BotUserService($this->telegram, $this->updates);
            $botUser->index();
            die();
        }
        return $this->botUser;

    }


    /**
     * @throws RequestException
     */
    private function adminStart()
    {
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Добро пожаловать уважаемый(-ая)") . " {$this->updates->getFirstName()}",
        ]);
    }

    /**
     * @throws RequestException
     */
    private function userStart()
    {
        $this->sendMainMenu('Здравствуйте дорогой пользователь');
    }
}
