<?php


namespace App\Services\TelegramServices;

use Illuminate\Http\Client\RequestException;

/**
 * Class LanguageService
 * @package App\Services\TelegramServices
 */
class LanguageService extends BotActionService
{

    /**
     * @throws RequestException
     */
    public function index()
    {
        switch ($this->action->SubAction) {
            case SubactionsService::ENTER_TEXT:
                $this->getLangsList();
                break;
            case SubactionsService::GET_TEXT:
                $this->setLang();
                break;
        }
    }

    /**
     * @throws RequestException
     */
    private function getLangsList()
    {
        $this->action->SubAction = SubactionsService::GET_TEXT;
        $this->action->save();

        $this->telegram->send("sendMessage", [
            'chat_id' => $this->updates->getFromId(),
            'text' => __("Выберите язык"),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::inlineLanguagesButton(app()->getLocale()),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    /**
     * @throws RequestException
     */
    private function setLang()
    {

        if ($this->updates->getText() == __("Назад")) {
            $this->action->Action = null;
            $this->action->SubAction = null;
            $this->action->save();
            $this->telegram->send("sendMessage", [
                'chat_id' => $this->updates->getFromId(),
                'text' => __("Меню"),
                'reply_markup' => json_encode([
                    'keyboard' => KeyboardsService::mainButtons(),
                    'resize_keyboard' => true,
                ]),
            ]);
            die();
        }

        if (in_array($this->updates->getText(), [
            trans_choice("Русский язык", 0), trans_choice("Русский язык", 1),
            trans_choice("Узбекский язык, латиница", 0), trans_choice("Узбекский язык, латиница", 1),
            trans_choice("Узбекский язык, кирилица", 0), trans_choice("Узбекский язык, кирилица", 1)
        ])) {
            $this->action->Action = null;
            $this->action->SubAction = null;
            $this->action->save();
            switch ($this->updates->getText()) {
                case trans_choice("Русский язык", 0):
                case trans_choice("Русский язык", 1):
                    $lang = "ru";
                    break;
                case trans_choice("Узбекский язык, латиница", 0):
                case trans_choice("Узбекский язык, латиница", 1):
                    $lang = "uz";
                    break;
                case trans_choice("Узбекский язык, кирилица", 0):
                case trans_choice("Узбекский язык, кирилица", 1):
                    $lang = "oz";
                    break;
            }

            $this->botUser->Lang = $lang;
            $this->botUser->save();

            app()->setLocale($lang);

            $this->sendMainMenu();
        } else {
            $this->telegram->send("sendMessage", [
                'chat_id' => $this->updates->getFromId(),
                'text' => __("Выберите правильный язык"),
            ]);
        }
    }
}
