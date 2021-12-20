<?php


namespace App\Services\TelegramServices;


use App\Constants\ControlActionsConstant;
use App\Constants\LanguageConstant;
use App\Constants\MainMenuConstant;
use App\Constants\MediaTypesConstant;
use App\Constants\UserRoleConstant;
use App\Models\Category;
use App\Models\Media;

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
            // [
            //     [
            //         'text' => __(MainMenuConstant::SETTINGS),
            //         'request_location' => true,
            //     ],
            // ],
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
    public static function returnBackButton(): array
    {
        return [
            [
                [
                    'text' => __(ControlActionsConstant::BACK),
                    'callback_data' => 'back',
                ]
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
                    'text' => __(ControlActionsConstant::BACK),
                ]
            ]
        ];
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getCategoriesList(string $type = MediaTypesConstant::VIDEO): array
    {
        $categories = Category::query()
            ->where('type', '=', $type)
            ->pluck("name_" . app()->getLocale())
            ->toArray();
        $array_list = [];
        $temp = [];
        foreach ($categories as $key => $category) {
            array_push($temp, [
                'text' => $category,
                'callback_data' => "{$type}_1"
            ]);
            if ($key % 2 == 1 || sizeof($categories) - 1 === $key) {
                array_push($array_list, $temp);
                $temp = [];
            }
        }
        array_push($array_list, [self::returnBackButton()[0][0]]);

        return $array_list;
    }

    public static function attachLabel(Media $media): array
    {
        $prev = $media->prev($media) ? $media->prev($media)->id : "";
        $next = $media->next($media) ? $media->next($media)->id : "";
        return [
            [
                [
                    'text' => __('prev'),
                    'callback_data' => "media_{$prev}"
                ],
                [
                    'text' => __('next'),
                    'callback_data' => "media_{$next}"
                ],
            ]
        ];
    }
}
