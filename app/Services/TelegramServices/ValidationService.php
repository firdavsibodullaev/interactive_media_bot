<?php


namespace App\Services\TelegramServices;

use App\Constants\LanguageConstant;
use App\Services\TelegramServices\Telegram\TelegramSettings;

/**
 * Class ValidationService
 * @package App\Services\TelegramServices
 */
class ValidationService
{


    /**
     * @param string $text
     * @return bool
     */
    public static function validateName(string $text): bool
    {
        if (
            (preg_match("/[!@#$%^&*()_\-+=\d№;:?\"\s]/", $text)
                || strlen($text) > 30)
            || in_array($text, LanguageConstant::translateList())
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param string $text
     * @param bool $is_contact
     * @return bool
     */
    public static function validatePhone(string $text, bool $is_contact = true): bool
    {
        if (
            (preg_match("/^\+998\d{9}$/", $text) || preg_match("/^998\d{9}$/", $text))
            && $is_contact
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param string $text
     * @return bool
     */
    public static function validateTitle(string $text): bool
    {
        if (strlen($text) < 255)
            return true;
        return false;
    }


    /**
     * @param string $text
     * @return bool
     */
    public static function validateText(string $text): bool
    {
        if (strlen($text) < 1500)
            return true;
        return false;
    }

    public static function validateLang(string $text): bool
    {
        if (in_array($text, TelegramSettings::langs())) {
            return true;
        }
        return false;
    }
}
