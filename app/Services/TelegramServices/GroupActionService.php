<?php

namespace App\Services\TelegramServices;

use App\Models\Action;
use App\Models\Appeal;
use App\Models\BotUser;
use App\Models\Chat;
use App\Models\GroupMessage;
use App\Services\TelegramServices\Telegram\Api;
use App\Services\TelegramServices\Telegram\TelegramSettings;
use App\Services\TelegramServices\Telegram\WebhookUpdates;
use Illuminate\Http\Client\RequestException;

/**
 * Class GroupActionService
 * @package App\Services\TelegramServices
 */
class GroupActionService
{
    /**
     * @var Api
     */
    private $telegram;
    /**
     * @var WebhookUpdates
     */
    private $updates;
    /**
     * @var int
     */
    private $chatId;
    /**
     * @var int
     */
    private $fromId;
    /**
     * @var string
     */
    private $text;
    /**
     * @var Action
     */
    private $botUser;

    /**
     * GroupActionService constructor.
     * @param Api $telegram
     * @param WebhookUpdates $updates
     */
    public function __construct(Api $telegram, WebhookUpdates $updates)
    {
        $this->telegram = $telegram;
        $this->updates = $updates;
        $this->chatId = $this->updates->getChatId();
        $this->fromId = $this->updates->getFromId();
        $this->text = $this->updates->getText();
        $this->botUser = BotUser::query()->where('ChatId', '=', $this->fromId)->firstOrCreate([
            'ChatId' => $this->fromId
        ]);
    }

    /**
     * @throws RequestException
     */
    public function index()
    {
        if ($this->updates->isGroup()) {
            //
        }

        $this->answer();
    }

    /**
     * @throws RequestException
     */
    public function answer()
    {
        if (isset($this->updates->json()['message']['reply_to_message'])) {

            $replyMessageId = $this->updates->json()['message']['reply_to_message']['message_id'];
            $message = GroupMessage::query()
                ->where('MessageToGroupId', '=', $replyMessageId)
                ->first();
            if ($message) {
                $file = $this->downloadFile();
                $fileType = [
                    'method' => 'sendMessage',
                    'type' => ''
                ];
                if ($isFile = is_array($file)) {
                    $fileType = TelegramSettings::fileTypes($file['file_type']);
                }
                $messageA = $this->telegram->send($fileType['method'], [
                    'chat_id' => $message->appeal->ChatId,
                    'reply_to_message_id' => $message->MessageId,
                    'text' => $this->text,
                    'caption' => $this->text,
                    $fileType['type'] => $isFile ? $this->updates->getFile()->getFileId() : null,
                ]);
                Appeal::query()->create([
                    'ChatId' => $this->updates->getFromId(),
                    'Text' => $this->text,
                    'IsFile' => $isFile,
                    'FileId' => $isFile ? $this->updates->getFile()->getFileId() : null,
                    'FileName' => $file['file_name'] ?? null,
                    'FileType' => $fileType['type'],
                    'OriginalMessageId' => $this->updates->getMessageId(),
                    'MessageId' => $messageA['result']['message_id'],
                    'SupportChatId' => $message->appeal->ChatId,
                    'IsAnswer' => ConstantsService::STATUS_ACTIVE,
                    'Status' => ConstantsService::STATUS_ACTIVE,
                    'IsSent' => ConstantsService::STATUS_ACTIVE,
                ]);
            }
        }
    }

    /**
     * @return array|bool|string
     */
    public function downloadFile()
    {
        if ($this->updates->isFile()) {
            return $this->updates->getFile()->downloadFile('Answer', 'appeals/answers');
        }
        return false;
    }
}
