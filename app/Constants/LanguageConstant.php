<?php


namespace App\Constants;


class LanguageConstant
{
    const UZ = 'uz';

    const RU = 'ru';

    /**
     * Список языков
     * @return string[]
     */
    public static function list(): array
    {
        return [
            self::UZ,
            self::RU
        ];
    }

    /**
     * @param int|null $number
     * @return array
     */
    public static function translateList(?int $number = 0): array
    {
        return [
            trans_choice(self::UZ, $number),
            trans_choice(self::RU, $number),
        ];
    }

    /**
     * @param int|null $number
     * @param $key
     * @return string
     */
    public static function keyList($key, ?int $number = 0): string
    {
        return [
            trans_choice(self::UZ, $number) => self::UZ,
            trans_choice(self::RU, $number) => self::RU,
        ][$key];
    }
}
