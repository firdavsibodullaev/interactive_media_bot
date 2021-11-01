<?php


namespace App\Constants;


use Illuminate\Support\Arr;

class MainMenuConstant
{
    const CONTACT = 'contact';

    const LOCATION = 'location';

    const VIDEO = 'video';

    const PHOTO = 'photo';

    const SETTINGS = 'settings';

    const BOT_CONTROL = 'control';

    /**
     * @return string[]
     */
    public static function list(): array
    {
        return [
            self::CONTACT,
            self::LOCATION,
            self::VIDEO,
            self::PHOTO,
            self::SETTINGS,
            self::BOT_CONTROL
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
