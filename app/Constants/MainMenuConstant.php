<?php


namespace App\Constants;


class MainMenuConstant
{
    const CONTACT = 'contact';

    const VIDEO = 'video';

    const PHOTO = 'photo';

    const SETTINGS = 'settings';

    /**
     * @return string[]
     */
    public static function list(): array
    {
        return [
            self::CONTACT,
            self::VIDEO,
            self::PHOTO,
            self::SETTINGS,
        ];
    }
}
