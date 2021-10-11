<?php


namespace App\Constants;


use Illuminate\Support\Arr;

class BotControlSettingsConstant
{

    const VIDEO_EDIT = 'video edit';

    const PHOTO_EDIT = 'photo edit';

    const CONTACT_EDIT = 'contact edit';

    /**
     * @return string[]
     */
    public static function list(): array
    {
        return [
            self::VIDEO_EDIT,
            self::PHOTO_EDIT,
            self::CONTACT_EDIT,
        ];
    }

    /**
     * @param string $except
     * @return array
     */
    public static function filteredList(string $except): array
    {
        $key = array_flip(self::list())[$except];
        return Arr::except(self::list(), $key);
    }
}
