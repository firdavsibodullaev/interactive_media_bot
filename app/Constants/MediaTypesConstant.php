<?php


namespace App\Constants;


class MediaTypesConstant
{
    const VIDEO = 'video';

    const IMAGE = 'image';

    public static function list()
    {
        return [
            self::VIDEO,
            self::IMAGE,
        ];
    }
}
