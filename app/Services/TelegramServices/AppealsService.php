<?php

namespace App\Services\TelegramServices;

use App\Constants\SettingsConstants;
use App\Models\Appeal;
use App\Models\Chat;
use App\Models\GroupMessage;
use App\Models\Settings;
use App\Services\TelegramServices\Telegram\TelegramSettings;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Storage;

/**
 * Class AppealsService
 * @package App\Services\TelegramServices
 */
class AppealsService extends BotActionService
{
    public function index()
    {
        $this->setActionWithMainMenu();

        $this->changeLanguage();

        $this->enterText();

        $this->getText();
    }

    private function setActionWithMainMenu()
    {
        if (in_array($this->text, TelegramSettings::mainMenu())) {
            $this->action->Action = TelegramSettings::getActionWithMainMenu($this->text);
            $this->action->SubAction = SubactionsService::ENTER_TEXT;
            $this->action->save();
        }
    }

    /**
     * @throws RequestException
     */
    private function enterText()
    {
        if ($this->action->SubAction === SubactionsService::ENTER_TEXT) {
            $this->action->SubAction = SubactionsService::GET_TEXT;
            $this->action->save();
            $this->clearTrash();
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->fromId,
                'text' => TelegramSettings::getMainMessageOfActions($this->action->Action),
                'reply_markup' => json_encode([
                    'keyboard' => KeyboardsService::sendRequestOrReturnBack(),
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ]),
            ]);
            die();
        }
    }

    /**
     * @throws RequestException
     */
    private function getText()
    {
        if ($this->action->SubAction === SubactionsService::GET_TEXT) {

            if ($this->text === __('Назад')) {
                $this->returnBack();
                return;
            }

            if ($this->text === __('Отправить')) {
                $this->sendAppeals();
                $this->returnBack();
                return;
            }

            $this->saveData();
        }
    }

    /**
     * @throws RequestException
     */
    public function sendAppeals()
    {
        /** @var Appeal $appeals */
        $appeals = Appeal::query()
            ->where('ChatId', '=', $this->chatId)
            ->where('IsSent', '=', ConstantsService::STATUS_PASSIVE)
            ->get();


        $this->send($appeals);

        Appeal::query()
            ->where('ChatId', '=', $this->chatId)
            ->where('IsSent', '=', ConstantsService::STATUS_PASSIVE)
            ->update(['IsSent' => ConstantsService::STATUS_ACTIVE]);
    }

    /**
     * @throws RequestException
     */
    public function send($appeals)
    {
        if ($this->action->Action === ActionsService::IDEA) {
            $this->sendToAdmins($appeals);
        } else {
            $this->sendToGroup($appeals);
        }
    }

    /**
     * @throws RequestException
     */
    private function sendToAdmins($appeals)
    {
        foreach ($appeals as $appeal) {
            $text = $this->generateMessageText($appeal->Text);
            $fileType = TelegramSettings::fileTypes($appeal->FileType);
            foreach (TelegramSettings::admins() as $admin) {
                $messageA = $this->telegram->send($fileType['method'], [
                    'chat_id' => $admin,
                    'text' => $text,
                    'caption' => $text,
                    $fileType['type'] => $appeal->FileId
                ]);
                GroupMessage::query()->create([
                    'MessageId' => $appeal->MessageId,
                    'MessageToGroupId' => $messageA['result']['message_id'],
                ]);
            }
        }
    }

    /**
     * @throws RequestException
     */
    private function sendToGroup($appeals)
    {
        foreach ($appeals as $appeal) {
            $groupId = Settings::query()
                ->where('Name', '=', SettingsConstants::SUPER_GROUP_ID)
                ->first()->Value;
            $text = $this->generateMessageText($appeal->Text);
            $fileType = TelegramSettings::fileTypes($appeal->FileType);
            $messageA = $this->telegram->send($fileType['method'], [
                'chat_id' => $groupId,
                'text' => $text,
                'caption' => $text,
                'parse_mode' => 'html',
                $fileType['type'] => $appeal->FileId
            ]);
            GroupMessage::query()->create([
                'MessageId' => $appeal->MessageId,
                'MessageToGroupId' => $messageA['result']['message_id'],
            ]);
        }
    }

    /**
     * @return void
     */
    private function saveData(): void
    {
        $chat = Chat::query()->firstOrCreate(['ChatId' => $this->fromId]);

        if ($isFile = $this->updates->isFile()) {
            $file = $this->updates->getFile()->downloadFile($this->action->Action, 'appeals');
        }

        Appeal::query()->create([
            'MessageId' => $this->updates->json()['message']['message_id'],
            'Text' => $this->text,
            'Action' => $this->action->Action,
            'ChatId' => $this->chatId,
            'SupportChatId' => $chat->Id,
            'Status' => ConstantsService::STATUS_ACTIVE,
            'IsFile' => $isFile
                ? ConstantsService::STATUS_ACTIVE
                : ConstantsService::STATUS_PASSIVE,
            'FileId' => $isFile ? $this->updates->getFile()->getFileId() : null,
            'FileName' => $isFile ? $file['file_name'] : null,
            'FileType' => $isFile ? $file['file_type'] : null,
        ]);
    }

    /**
     * @throws RequestException
     */
    public function returnBack()
    {
        $this->action->Action = null;
        $this->action->SubAction = null;
        $this->action->save();

        $this->sendMainMenu();
    }

    /**
     * @throws RequestException
     */
    public function changeLanguage()
    {
        if ($this->action->Action == ActionsService::LANGUAGE) {
            $language = new LanguageService($this->telegram, $this->updates);
            $language->index();
            die();
        }
    }

    private function clearTrash()
    {
        $appeals = Appeal::query()
            ->where('ChatId', '=', $this->chatId)
            ->where('IsSent', '=', ConstantsService::STATUS_PASSIVE)
            ->get();
        foreach ($appeals as $appeal) {
            Storage::delete("appeals/{$appeal->FileName}");
            $appeal->delete();
        }
    }

    /**
     * @param string $text
     * @return string
     */
    private function generateMessageText(string $text): string
    {

        $message = "<strong>{$this->botUser->LastName} {$this->botUser->FirstName}</strong>"
            . PHP_EOL . "<strong>Организация</strong>: {$this->botUser->Organization}"
            . PHP_EOL . "<strong>Должность</strong>: {$this->botUser->Post}"
            . PHP_EOL . "<strong>Номер телефона</strong>: {$this->botUser->Phone}";

        $message .= PHP_EOL . PHP_EOL . $text;
        $message .= PHP_EOL . PHP_EOL . now()->format('d-m-Y');

        return $message;
    }
}
