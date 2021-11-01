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
}
