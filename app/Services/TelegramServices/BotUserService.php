<?php


namespace App\Services\TelegramServices;

use App\Models\BotUser;
use App\Services\TelegramServices\Telegram\TelegramSettings;
use Illuminate\Http\Client\RequestException;

/**
 * Class BotUserService
 * @package App\Services\TelegramServices
 */
class BotUserService extends BotActionService
{

    /**
     * @throws RequestException
     */
    public function index()
    {
        if ($this->action->Action != ActionsService::BOT_USER) {
            $this->action->Action = ActionsService::BOT_USER;
            $this->action->SubAction = SubactionsService::BOT_USER_CREATE;
            $this->action->save();
        }
        switch ($this->action->SubAction) {
            case SubactionsService::BOT_USER_CREATE:
                $this->create();
                break;
            case SubactionsService::BOT_USER_GET_LANG:
                $this->getLang();
                break;
            case SubactionsService::BOT_USER_ENTER_NAME:
                $this->enterName();
                break;
            case SubactionsService::BOT_USER_ENTER_SURNAME:
                $this->enterSurname();
                break;
            case SubactionsService::BOT_USER_GET_PHONE:
                $this->getPhone();
                break;
            case SubactionsService::BOT_USER_GET_ORGANIZATION:
                $this->getOrganization();
                break;
            case SubactionsService::BOT_USER_GET_POST:
                $this->getPost();
                break;
        }
    }

    /**
     * @throws RequestException
     */
    private function create()
    {
        $this->action->SubAction = SubactionsService::BOT_USER_GET_LANG;
        $this->action->save();
        BotUser::query()->updateOrCreate([
            'ChatId' => $this->chatId,
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => "Здравствуйте! Давайте для начала выберем язык обслуживания!"
                . PHP_EOL . PHP_EOL . "Assalomu alaykum! Keling, boshlanishiga xizmat ko‘rsatish tilini tanlaymiz!",
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
    private function getLang()
    {
        if (!ValidationService::validateLang($this->text)) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => __("Выберите правильный язык")
            ]);
            die();
        }
        $this->action->SubAction = SubactionsService::BOT_USER_ENTER_NAME;
        $this->action->save();

        $lang = TelegramSettings::getLang($this->text);
        app()->setLocale($lang);

        BotUser::query()->where('ChatId', '=', $this->chatId)->update([
            'Lang' => $lang,
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Ввод имени")
        ]);
    }

    /**
     * @throws RequestException
     */
    private function enterName()
    {
        if (!ValidationService::validateName($this->text)) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => __("Введите имя правильно")
            ]);
            die();
        }

        $this->action->SubAction = SubactionsService::BOT_USER_ENTER_SURNAME;
        $this->action->save();

        BotUser::query()->where('ChatId', '=', $this->chatId)->update([
            'FirstName' => $this->text,
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Отправка фамилии"),
        ]);
    }

    /**
     * @throws RequestException
     */
    public function enterSurname()
    {
        if (!ValidationService::validateName($this->text)) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => __("Введите Фамилию правильно")
            ]);
            die();
        }

        $this->action->SubAction = SubactionsService::BOT_USER_GET_PHONE;
        $this->action->save();

        BotUser::query()->where('ChatId', '=', $this->chatId)->update([
            'LastName' => $this->text,
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Отправка номера"),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::phoneNumberButton(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @throws RequestException
     */
    private function getPhone()
    {
        $contact = $this->updates->getContact();
        if (!ValidationService::validatePhone($contact)) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chatId,
                'text' => __("Ввод номера телефона правильно")
            ]);
            die();
        }

        $this->action->SubAction = SubactionsService::BOT_USER_GET_ORGANIZATION;
        $this->action->save();

        BotUser::query()
            ->where('ChatId', '=', $this->chatId)
            ->update([
                'Phone' => str_contains($contact, '+') ? $contact : "+" . $contact,
            ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Ввод организаци"),
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getOrganization()
    {
        $this->action->SubAction = SubactionsService::BOT_USER_GET_POST;
        $this->action->save();

        BotUser::query()->where('ChatId', '=', $this->chatId)->update([
            'Organization' => $this->text,
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chatId,
            'text' => __("Ввод должности"),
        ]);

    }

    /**
     * @throws RequestException
     */
    public function getPost()
    {
        $this->action->Action = null;
        $this->action->SubAction = null;
        $this->action->save();

        BotUser::query()
            ->where('ChatId', '=', $this->chatId)
            ->update([
                'Post' => $this->text,
                'Status' => ConstantsService::STATUS_ACTIVE,
//                'Admin' => TelegramSettings::isAdmin($this->chatId)
//                    ? ConstantsService::STATUS_ACTIVE
//                    : ConstantsService::STATUS_PASSIVE,
            ]);
        $this->sendMainMenu();
    }
}
