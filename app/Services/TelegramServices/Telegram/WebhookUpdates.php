<?php


namespace App\Services\TelegramServices\Telegram;

/**
 * Class WebhookUpdates
 * @package App\Services\TelegramServices\Telegram
 */
class WebhookUpdates
{

    /**
     * @var array
     */
    private $update;

    /**
     * @var bool
     */
    private $my_chat_member;

    /**
     * @var bool
     */
    private $callback_query;

    /**
     * @var int
     */
    private $from_id;
    /**
     * @var bool
     */
    private $is_channel;


    /**
     * WebhookUpdates constructor.
     * @param array $update
     * @param string $bot_type
     */
    public function __construct(array $update, string $bot_type)
    {
        $this->update = $update;
        $is_channel = isset($update["channel_post"]) ?? false;
        if ($is_channel && $bot_type != "channel") {
            die();
        }

        $my_chat_member = isset($this->update['my_chat_member']);
        $callback_query = isset($this->update['callback_query']);

        $this->my_chat_member = $my_chat_member;
        $this->callback_query = $callback_query;
        $this->from_id = $this->getFromId();
    }

    /**
     * @return bool
     */
    public function isChannel(): bool
    {
        return $this->is_channel;
    }

    /**
     * @return bool
     */
    public function myChatMember(): bool
    {
        return $this->my_chat_member;
    }

    public function isCommand(): bool
    {
        if (preg_match('/^\/\w+/', $this->getText())) return true;
        return false;
    }

    /**
     * @return string
     */
    public function myChatMemberStatus(): string
    {
        if ($this->my_chat_member) {
            return $this->update['my_chat_member']['new_chat_member']['status'];
        } else {
            return "";
        }
    }

    /**
     * @return bool
     */
    public function callbackQuery(): bool
    {
        return $this->callback_query;
    }

    /**
     * @return int
     */
    public function getFromId(): int
    {
        if ($this->my_chat_member) {
            return $this->update["my_chat_member"]["from"]["id"];
        } elseif ($this->callback_query) {
            return $this->update["callback_query"]["from"]["id"];
        }
//        elseif ($this->is_channel) {
//            return $this->update["channel_post"][""];
//        }
        if (isset($this->update["message"])) {
            return $this->update["message"]["from"]["id"];
        } else {
            if ($this->isEditedMessage()) {
                return $this->update['edited_message']['from']['id'];
            }

            die();
        }
    }

    public function getChatId(): int
    {
        if ($this->my_chat_member) {
            return $this->update["my_chat_member"]["chat"]["id"];
        } elseif ($this->callback_query) {
            return $this->update["callback_query"]["chat"]["id"];
        }
//        elseif ($this->is_channel) {
//            return $this->update["channel_post"][""];
//        }
        if (isset($this->update["message"])) {
            return $this->update["message"]["chat"]["id"];
        } else {
            if ($this->isEditedMessage()) {
                return $this->update['edited_message']['chat']['id'];
            }
            die();
        }
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        if ($this->isEditedMessage()) {
            return $this->update['edited_message']['from']['first_name'] ?? "";
        }
        return $this->update['message']['from']['first_name'] ?? "";
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        if ($this->isEditedMessage()) {
            return $this->update['edited_message']['from']['last_name'] ?? "";
        }
        return $this->update['message']['from']['last_name'] ?? "";
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        if ($this->isEditedMessage()) {
            return $this->update['edited_message']['from']['username'] ?? "";
        }
        return $this->update['message']['from']['username'] ?? "";
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        if ($this->callback_query)
            return $this->update['callback_query']['message']['text']
                ?? ($this->update['callback_query']['message']['text'] ?? "");


        if ($this->isEditedMessage()) {
            return $this->update['edited_message']['caption']
                ?? ($this->update['edited_message']['text'] ?? "");
        }

        return $this->update['message']['caption']
            ?? ($this->update['message']['text'] ?? "");
    }


    /**
     * @return string|null
     */
    public function getCallbackQueryData(): ?string
    {
        if ($this->callback_query) {
            return $this->update["callback_query"]["data"];
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function json(): array
    {
        return $this->update;
    }

    /**
     * @return false|string
     */
    public function body()
    {
        return json_encode($this->update);
    }


    /**
     * @return bool
     */
    public function isContact(): bool
    {
        return isset($this->update["message"]["contact"]);
    }

    /**
     * @return string|null
     */
    public function getContact(): ?string
    {
        return $this->isContact()
            ? preg_replace('/\+/', '', $this->update["message"]["contact"]["phone_number"])
            : $this->getText();
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        $types = ["animation", "audio", "document", "photo", "sticker", "video", "voice"];
        $is_set = false;
        foreach ($types as $type) {
            if (isset($this->update["message"][$type]))
                $is_set = true;
        }
        return $is_set;
    }

    /**
     * @return Files|bool
     */
    public function getFile()
    {
        if ($this->isFile()) {
            return new Files($this->update);
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isGroup(): bool
    {
        $groups = ['group', 'supergroup'];
        if ($this->isEditedMessage()) {
            return in_array($this->update["edited_message"]["chat"]["type"], $groups);
        }
        return in_array($this->update["message"]["chat"]["type"], $groups);
    }

    /**
     * @return bool
     */
    public function isEditedMessage(): bool
    {
        return isset($this->update['edited_message']);
    }

    /**
     * @return bool
     */
    public function isLocation(): bool
    {
        return isset($this->update['message']['location']);
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        if ($this->isEditedMessage()) {
            return $this->update['edited_message']['message_id'];
        }
        if ($this->callbackQuery()) {
            return $this->update['callback_query']['message']['message_id'];
        }
        return $this->update['message']['message_id'];
    }

    public function getLocation()
    {
        if ($this->isEditedMessage()) {
            return $this->getText();
        }
        if (!$this->isLocation()) {
            return $this->getText();
        }
        return $this->update['message']['location'];
    }
}
