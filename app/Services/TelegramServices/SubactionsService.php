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

    /**
     * Раздел для настроек бота
     */
    public const CONTACT_EDIT_INDEX = 'contact.edit.index';

    /**  */
    public const GET_MAIN_BUTTONS = 'get.main.buttons';

    public const GET_CONTACT_EDIT_ACTION = 'get.contact.edit.action';

    public const EDIT_CONTACT_GET_INSTAGRAM = 'get.contact.get.instagram';

    public const EDIT_CONTACT_GET_TELEGRAM = 'get.contact.get.telegram';

    public const EDIT_CONTACT_GET_PHONE = 'get.contact.get.phone';

    public const EDIT_CONTACT_GET_LOCATION = 'get.contact.get.location';

    public const GET_MEDIA = 'get.media';

    public const GET_MEDIA_CATEGORY = 'get.media.category';

}
