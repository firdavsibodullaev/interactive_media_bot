<?php


namespace App\Services\TelegramServices;


/**
 * Class KeyboardsService
 * @package App\Services\TelegramServices
 */
class KeyboardsService
{
    /**
     * @return array
     */
    public static function mainButtons(): array
    {
        return [
            [
                [
                    'text' => __("Вопросы по заявкам"),
                ],
                [
                    'text' => __("Направить идею по улучшению сервиса"),
                ],
            ],
            [
                [
                    'text' => __("Изменить язык"),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public static function phoneNumberButton(): array
    {
        return [
            [
                [
                    'text' => __("Поделиться номером"),
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

    public static function languagesButtons(): array
    {
        return [
            [
                [
                    'text' => trans_choice("Узбекский язык, латиница", 0),
                ],
            ],
            [
                [
                    'text' => trans_choice("Узбекский язык, кирилица", 0),
                ],
                [
                    'text' => trans_choice("Русский язык", 0),
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
}
