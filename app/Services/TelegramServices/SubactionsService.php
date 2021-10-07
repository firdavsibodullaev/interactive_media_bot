<?php


namespace App\Services\TelegramServices;

/**
 * Class SubactionsService
 * @package App\Services\TelegramServices
 */
class SubactionsService
{
    /**
     * Раздел регистрации
     */
    public const BOT_USER_CREATE = 'bot.user.create';

    /**
     * Раздел получения языка и отправки имени
     */
    public const BOT_USER_GET_LANG = 'bot.user.get.lang';

    /**
     * Раздел получения имени и отправки фамилии
     */
    public const BOT_USER_ENTER_NAME = 'bot.user.enter.name';

    /**
     * Раздел получения фамилии и отправки номера телефона
     */
    public const BOT_USER_ENTER_SURNAME = 'bot.user.enter.surname';

    /**
     * Раздел получения номера телефона
     */
    public const BOT_USER_GET_PHONE = 'bot.user.get.phone';

    /**
     * Раздел получения организации
     */
    public const BOT_USER_GET_ORGANIZATION = 'bot.user.get.organization';

    /**
     * Раздел получения должности
     */
    public const BOT_USER_GET_POST = 'bot.user.get.post';

    /**
     * Раздел для отправки текста
     */
    public const ENTER_TEXT = 'enter.text';

    /**
     * Раздел для получения текста
     */
    public const GET_TEXT = 'get.text';

    /**
     * Раздел для изменения языка
     */
    public const CHANGE_LANGUAGE = 'change.language';


    /**
     * Раздел для изменения языка
     */
    public const SET_LANGUAGE = 'set.language';

}
