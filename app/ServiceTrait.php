<?php


namespace App;

use App\Constants\MainMenuConstant;
use App\Constants\UserRoleConstant;
use App\Services\TelegramServices\ActionsService;
use App\Services\TelegramServices\ContactService;
use App\Services\TelegramServices\ControlService;
use App\Services\TelegramServices\LocationService;
use App\Services\TelegramServices\PhotoService;
use App\Services\TelegramServices\VideoService;
use Illuminate\Http\Client\RequestException;

trait ServiceTrait
{

    /**
     * @throws RequestException
     */
    public function actions()
    {
        if ($this->isAction(MainMenuConstant::CONTACT)) {
            (new ContactService($this->telegram, $this->updates))->index();
        } elseif ($this->isAction(MainMenuConstant::LOCATION)) {
            (new LocationService($this->telegram, $this->updates))->index();
        } elseif ($this->isAction(MainMenuConstant::VIDEO)) {
            (new VideoService($this->telegram, $this->updates))->index();
        } elseif ($this->isAction(MainMenuConstant::PHOTO)) {
            (new PhotoService($this->telegram, $this->updates))->index();
        }
        if ($this->bot_user->role === UserRoleConstant::ADMIN) {
            if ($this->isAction(MainMenuConstant::BOT_CONTROL)) {
                (new ControlService($this->telegram, $this->updates))->index();
            }
        }
    }


    /**
     * @param string $action
     * @return bool
     */
    public function isAction(string $action): bool
    {
        return [
            MainMenuConstant::CONTACT => (
                ($this->text === __(MainMenuConstant::CONTACT)
                    || $this->getAction()->action === ActionsService::CONTACT)
                && !in_array(MainMenuConstant::CONTACT, MainMenuConstant::filteredList(MainMenuConstant::CONTACT))
            ),
            MainMenuConstant::LOCATION => (
                ($this->text === __(MainMenuConstant::LOCATION)
                    || $this->getAction()->action === ActionsService::LOCATION)
                && !in_array(MainMenuConstant::LOCATION, MainMenuConstant::filteredList(MainMenuConstant::LOCATION))
            ),
            MainMenuConstant::VIDEO => (
                ($this->text === __(MainMenuConstant::VIDEO)
                    || $this->getAction()->action === ActionsService::VIDEO)
                && !in_array(MainMenuConstant::VIDEO, MainMenuConstant::filteredList(MainMenuConstant::VIDEO))
            ),
            MainMenuConstant::PHOTO => (
                ($this->text === __(MainMenuConstant::PHOTO)
                    || $this->getAction()->action === ActionsService::PHOTO)
                && !in_array(MainMenuConstant::PHOTO, MainMenuConstant::filteredList(MainMenuConstant::PHOTO))
            ),
            MainMenuConstant::BOT_CONTROL => (
                ($this->text === __(MainMenuConstant::BOT_CONTROL)
                    || $this->getAction()->action === ActionsService::BOT_CONTROL)
                && !in_array(MainMenuConstant::BOT_CONTROL, MainMenuConstant::filteredList(MainMenuConstant::BOT_CONTROL))
            ),
        ][$action];
    }
}
