<?php


namespace App\Services\TelegramServices;


use App\Constants\ControlActionsConstant;
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
        if ($role === UserRoleConstant::ADMIN) {
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
     * @return array
     */
    public static function phoneNumberOrBackButton(): array
    {
        return [
            [
                [
                    'text' => __("Telefon raqam bilan ulashish"),
                    'request_contact' => true,
                ]
            ],
            [
                [
                    'text' => __(ControlActionsConstant::BACK),
                ]
            ],
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
                    'text' => __(ControlActionsConstant::BACK),
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

    /**
     * @return \array[][]
     */
    public static function controlSettingsButtons(): array
    {
        return [
            [
                [
                    'text' => __(ControlActionsConstant::EDIT_VIDEO)
                ],
                [
                    'text' => __(ControlActionsConstant::EDIT_PHOTO)
                ],
            ],
            [
                [
                    'text' => __(ControlActionsConstant::EDIT_CONTACT),
                ],
            ],
            [
                [
                    'text' => __(ControlActionsConstant::BACK),
                ],
            ],
        ];
    }

    /**
     * @return \array[][]
     */
    public static function sendContactEditButtons(): array
    {
        return [
            [
                [
                    'text' => __(ControlActionsConstant::EDIT_PHONE),
                    'callback_data' => ControlActionsConstant::EDIT_PHONE,
                ],
                [
                    'text' => __(ControlActionsConstant::EDIT_INSTAGRAM),
                    'callback_data' => ControlActionsConstant::EDIT_INSTAGRAM,
                ],
            ],
            [
                [
                    'text' => __(ControlActionsConstant::EDIT_TELEGRAM),
                    'callback_data' => ControlActionsConstant::EDIT_TELEGRAM,
                ],
                [
                    'text' => __(ControlActionsConstant::EDIT_LOCATION),
                    'callback_data' => ControlActionsConstant::EDIT_LOCATION,
                ],
            ],
            [
                [
                    'text' => ControlActionsConstant::BACK,
                ]
            ]
        ];
    }

}
