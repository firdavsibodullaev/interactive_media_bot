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
    public const REGISTER_CREATE = 'register.create';

    /**
     * Раздел получения языка и отправки имени
     */
    public const REGISTER_GET_LANGUAGE = 'register.get.language';

    /**
     * Раздел получения имени и отправки фамилии
     */
    public const REGISTER_ENTER_NAME = 'register.enter.name';

    /**
     * Раздел получения фамилии и отправки номера телефона
     */
    public const REGISTER_ENTER_SURNAME = 'register.enter.surname';

    /**
     * Раздел получения номера телефона
     */
    public const REGISTER_SEND_PHONE = 'register.send.phone';

    /**
     * Раздел получения организации
     */
    public const REGISTER_GET_ORGANIZATION = 'register.get.organization';

    /**
     * Раздел получения должности
     */
    public const REGISTER_GET_POST = 'register.get.post';

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
