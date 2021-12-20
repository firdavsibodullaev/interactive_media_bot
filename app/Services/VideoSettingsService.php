<?php

namespace App\Services;

use App\Constants\ControlActionsConstant;
use App\Constants\MediaTypesConstant;
use App\Models\Category;
use App\Models\Media;
use App\Services\TelegramServices\KeyboardsService;
use App\Services\TelegramServices\SubactionsService;
use App\Services\TelegramServices\TelegramService;
use App\Services\TelegramServices\ValidationService;
use Illuminate\Http\Client\RequestException;

/**
 * Class VideoSettingsService
 * @package App\Services
 */
class VideoSettingsService extends TelegramService
{
    /**
     * @throws RequestException
     */
    public function index()
    {
        $this->setMainAction();

        $this->switchActions();
    }

    /**
     * @throws RequestException
     */
    public function switchActions()
    {
        switch ($this->action->sub_action) {
            case SubactionsService::GET_MAIN_BUTTONS:
                $this->getButtons();
                break;
            case SubactionsService::GET_MEDIA_CATEGORY:
                $this->getMediaCategory();
                break;
            case SubactionsService::GET_MEDIA:
                $this->getMedia();
                break;
        }
    }

    /**
     * @throws RequestException
     */
    public function getButtons()
    {
        $this->action->sub_action = SubactionsService::GET_MEDIA_CATEGORY;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Bo\'limni tanlang'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::getCategoriesList(),
                'resize_keyboard' => true
            ])
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getMediaCategory()
    {
        $category = Category::query()
            ->where('type', '=', MediaTypesConstant::VIDEO)
            ->where("name_" . app()->getLocale(), '=', $this->text)
            ->first();

        if (!$category) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __('Bo\'limni tanlang')
            ]);
            return;
        }

        Media::query()->updateOrCreate(['status' => false], [
            'category_id' => $category->id
        ]);

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Video jo\'nating'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::returnBackButton(),
                'resize_keyboard' => true
            ])
        ]);

        $this->action->sub_action = SubactionsService::GET_MEDIA;
        $this->action->save();

    }

    /**
     * @throws RequestException
     */
    public function getMedia()
    {
        if ($this->text === __(ControlActionsConstant::BACK)) {
            $this->controlMainMenu();
            return;
        }
        $file = $this->updates->getFile();
        if (!$file) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __('Video jo\'nating')
            ]);
            return;
        }
        $video = $file->getVideo();
        if (!ValidationService::validateVideo($video)) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __('Video jo\'nating')
            ]);
            return;
        }

        $this->saveMedia($video);


        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Agar video jo\'natib bo\'lgan bo\'lsangiz "Ortga" tugmasini bosing')
        ]);
    }

    /**
     * @throws RequestException
     */
    public function controlMainMenu()
    {
        $this->action->action_1 = null;
        $this->action->sub_action = null;
        $this->action->save();
        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('O\'zgarish tugmasini bosing'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::controlSettingsButtons(),
                'resize_keyboard' => true,
            ]),
        ]);
    }

    public function setMainAction()
    {
        if ($this->action->action_1 !== ActionOneService::VIDEO_EDIT) {
            $this->action->action_1 = ActionOneService::VIDEO_EDIT;
            $this->action->sub_action = SubactionsService::GET_MAIN_BUTTONS;
            $this->action->save();
        }
    }

    /**
     * @param array $video
     */
    private function saveMedia(array $video)
    {
        $media = Media::query()->where('status', '=', false)->first();
        if (!$media) {
            $media = Media::query()
                ->where('status', '=', true)
                ->orderByDesc('created_at')
                ->first();
            Media::query()->create([
                'file_id' => $video['file_id'],
                'type' => MediaTypesConstant::VIDEO,
                'status' => true,
                'category_id' => $media->category_id
            ]);
        } else {
            $media->file_id = $video['file_id'];
            $media->type = MediaTypesConstant::VIDEO;
            $media->status = true;
            $media->save();
        }
    }
}
