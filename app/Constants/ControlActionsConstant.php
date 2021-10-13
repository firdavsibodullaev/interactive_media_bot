<?php


namespace App\Constants;


class ControlActionsConstant
{

    const BACK = 'back';

    const EDIT_VIDEO = 'edit video';

    const EDIT_PHOTO = 'edit photo';

    const EDIT_CONTACT = 'edit contact';

    const EDIT_PHONE = 'edit phone';

    const EDIT_INSTAGRAM = 'edit instagram';

    const EDIT_TELEGRAM = 'edit telegram';

    const EDIT_LOCATION = 'edit location';

    /**
     * @param string $text
     * @return string
     */
    public static function getKey(string $text): string
    {
        $array = [
            __(self::EDIT_VIDEO) => self::EDIT_VIDEO,
            __(self::EDIT_PHOTO) => self::EDIT_PHOTO,
            __(self::EDIT_CONTACT) => self::EDIT_CONTACT,
            __(self::BACK) => self::BACK,
            __(self::EDIT_PHONE) => self::EDIT_PHONE,
            __(self::EDIT_TELEGRAM) => self::EDIT_TELEGRAM,
            __(self::EDIT_INSTAGRAM) => self::EDIT_INSTAGRAM,
            __(self::EDIT_LOCATION) => self::EDIT_LOCATION,
        ];

        return $array[$text] ?? "";
    }
}
