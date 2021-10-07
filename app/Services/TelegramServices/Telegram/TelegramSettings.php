<?php


namespace App\Services\TelegramServices\Telegram;

use App\Message;
use App\Services\TelegramServices\ActionsService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;

/**
 * Class TelegramSettings
 * @package App\Services\TelegramServices\Telegram
 */
class TelegramSettings
{
    /**
     * @return array
     */
    public static function mainMenu(): array
    {
        return [
            __("Вопросы по заявкам"),
            __("Направить идею по улучшению сервиса"),
            __("Изменить язык"),
        ];
    }

    /**
     * @return array
     */
    public static function associatedMainMenu(): array
    {
        return [
            ActionsService::APPEAL => __("Вопросы по заявкам"),
            ActionsService::IDEA => __("Направить идею по улучшению сервиса"),
            ActionsService::LANGUAGE => __("Изменить язык"),
        ];
    }

    /**
     * @param string|null $action
     * @return false|string
     */
    public static function getMainMenuWithAction(?string $action = '')
    {
        $menuList = [
            ActionsService::APPEAL => __("Вопросы по заявкам"),
            ActionsService::IDEA => __("Направить идею по улучшению сервиса"),
            ActionsService::LANGUAGE => __("Изменить язык"),
        ];
        return $menuList[$action] ?? false;
    }

    /**
     * @param string|null $menu
     * @return false|string
     */
    public static function getActionWithMainMenu(?string $menu = '')
    {
        $menuList = [
            __("Вопросы по заявкам") => ActionsService::APPEAL,
            __("Направить идею по улучшению сервиса") => ActionsService::IDEA,
            __("Изменить язык") => ActionsService::LANGUAGE,
        ];
        return $menuList[$menu] ?? false;
    }

    public static function getMainMessageOfActions(string $action)
    {
        return [
            ActionsService::APPEAL => __('Опишите вопросы по заявкам'),
            ActionsService::IDEA => __('Направить идею по улучшению сервиса'),
        ][$action];
    }

    /**
     * @param $type
     * @return bool|mixed
     */
    public static function fileTypes($type)
    {
        $sendMessage = ['method' => 'sendMessage', 'type' => ''];
        $types = [
            'document' => [
                'method' => 'sendDocument',
                'type' => 'document'
            ],
            'documents' => [
                'method' => 'sendDocument',
                'type' => 'document'
            ],
            'audio' => [
                'method' => 'sendAudio',
                'type' => 'audio'
            ],
            'music' => [
                'method' => 'sendAudio',
                'type' => 'audio'
            ],
            'video' => [
                'method' => 'sendVideo',
                'type' => 'video',
            ],
            'videos' => [
                'method' => 'sendVideo',
                'type' => 'video',
            ],
            'photos' => [
                'method' => 'sendPhoto',
                'type' => 'photo'
            ],
            'photo' => [
                'method' => 'sendPhoto',
                'type' => 'photo'
            ],
        ];

        return $types[$type] ?? $sendMessage;
    }

    /**
     * @return string[]
     */
    public static function mixFilesAccess(): array
    {
        return ['photo', 'video'];
    }

    /**
     * @param string $menu
     * @return array
     */
    public static function filtered(string $menu): array
    {
        $key = array_flip(self::mainMenu())[$menu];
        return Arr::except(self::mainMenu(), $key);
    }

    public static function admins(): array
    {
        return [
//            92018423,
            287956415,
        ];
    }

    /**
     * @param int $chat_id
     * @return bool
     */
    public static function isAdmin(int $chat_id): bool
    {
        if (in_array($chat_id, self::admins()))
            return true;
        return false;
    }

    /**
     * @return array
     */
    public static function langs(): array
    {
        return [
            trans_choice("Узбекский язык, латиница", 0),
            trans_choice("Узбекский язык, кирилица", 0),
            trans_choice("Русский язык", 0),
        ];
    }

    /**
     * @param string $text
     * @return string
     */
    public static function getLang(string $text): string
    {
        $lang = "";
        switch ($text) {
            case trans_choice("Узбекский язык, латиница", 0):
            case trans_choice("Узбекский язык, латиница", 1):
                $lang = "uz";
                break;
            case trans_choice("Узбекский язык, кирилица", 0):
            case trans_choice("Узбекский язык, кирилица", 1):
                $lang = "oz";
                break;
            case trans_choice("Русский язык", 0):
            case trans_choice("Русский язык", 1):
                $lang = "ru";
                break;
        }
        return $lang;
    }
}
