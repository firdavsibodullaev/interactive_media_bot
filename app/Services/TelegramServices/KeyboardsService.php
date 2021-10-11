<?php


namespace App\Services\TelegramServices;


use App\Constants\LanguageConstant;
use App\Constants\MainMenuConstant;
use App\Constants\UserRoleConstant;

/**
 * Class KeyboardsService
 * @package App\Services\TelegramServices
 */
class KeyboardsService
{
    /**
     * @param string|null $role
     * @return array
     */
    public static function mainButtons(?string $role = null): array
    {
        $button = [
            [
                [
                    'text' => __(MainMenuConstant::VIDEO),
                ],
                [
                    'text' => __(MainMenuConstant::PHOTO),
                ],
            ],
            [
                [
                    'text' => __(MainMenuConstant::CONTACT),
                ],
                [
                    'text' => __(MainMenuConstant::LOCATION),
                ],
            ],
            [
                [
                    'text' => __(MainMenuConstant::SETTINGS),
                    'request_location' => true,
                ],
            ],
        ];
        if ($role) {
            array_push($button, [
                [
                    'text' => __(MainMenuConstant::BOT_CONTROL),
                ]
            ]);
        }
        return $button;
    }

    /**
     * @return array
     */
    public static function phoneNumberButton(): array
    {
        return [
            [
                [
                    'text' => __("Telefon raqam bilan ulashish"),
                    'request_contact' => true,
                ]
            ]
        ];
    }


    /**
     * @param string $lang
     * @return array
     */
    public static function inlineLanguagesButton(string $lang): array
    {
        return [
            [
                [
                    'text' => ($lang == "uz") ? trans_choice("Узбекский язык, латиница", 1) : trans_choice("Узбекский язык, латиница", 0),
                ],
                [
                    'text' => ($lang == "ru") ? trans_choice("Русский язык", 1) : trans_choice("Русский язык", 0),
                ],
                [
                    'text' => ($lang == "oz") ? trans_choice("Узбекский язык, кирилица", 1) : trans_choice("Узбекский язык, кирилица", 0),
                ],
            ],
            [
                [
                    'text' => __("Назад"),
                ]
            ],
        ];
    }

    /**
     * @return \array[][]
     */
    public static function languagesButtons(): array
    {
        return [
            [
                [
                    'text' => trans_choice(LanguageConstant::UZ, 0),
                ],
                [
                    'text' => trans_choice(LanguageConstant::RU, 0),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public static function settingsButtons(): array
    {
        return [
            [
                [
                    'text' => __("Tilni o'zgartirish"),
                    'callback_data' => "change_language"
                ],
                [
                    'text' => __("Akkauntni o'chirish"),
                    'callback_data' => "delete_account"
                ]
            ],
            [
                [
                    'text' => __("Sozlamalardan chiqish"),
                    'callback_data' => "exit_settings"
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public static function returnBackButton(): array
    {
        return [
            [
                [
                    'text' => __("Назад"),
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public static function sendRequestOrReturnBack(): array
    {
        return [
            [
                [
                    'text' => __("Отправить"),
                ]
            ],
            [
                [
                    'text' => __("Назад"),
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public static function finishButton(): array
    {
        return [
            [
                [
                    'text' => __("Закончить"),
                    'callback_data' => 'finish'
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public static function skipOrCancel(): array
    {
        return [
            [
                [
                    'text' => __("Пропустить")
                ],
                [
                    'text' => __("Назад")
                ],
            ]
        ];
    }

    public static function controlSettingsButtons()
    {
        return [
            [
                [
                    'text' => __('Videoni o\'zgartirish')
                ],
                [
                    'text' => __('Rasmni o\'zgartirish')
                ],
            ],
            [
                [
                    'text' => __('Kontakni o\'zgartirish'),
                ],
            ],
        ];
    }
}
