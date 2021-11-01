<?php


namespace App\Constants;


class UserRoleConstant
{
    const CLIENT = 'client';

    const ADMIN = 'admin';

    /**
     * @return string[]
     */
    public static function list(): array
    {
        return [
            self::ADMIN,
            self::CLIENT,
        ];
    }
}
