<?php

namespace App\Services\TelegramServices;

use App\Constants\ControlActionsConstant;
use App\Constants\MediaTypesConstant;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Http\Client\RequestException;

/**
 * Class PhotoService
 * @package App\Services\TelegramServices
 */
class PhotoService extends TelegramService
{
    public function index()
    {
        $this->setMainAction();

        $this->switchActions();
    }

    /**
     * @throws RequestException
     */
    private function switchActions()
    {
        switch ($this->action->sub_action) {
            case SubactionsService::SET_MEDIA_CATEGORY:
                $this->sendMediaCategory();
                break;
            case SubactionsService::GET_MEDIA_CATEGORY:
                $this->getMediaCategory();
                break;
        }
    }

    /**
     * @throws RequestException
     */
    public function sendMediaCategory()
    {
        $this->action->sub_action = SubactionsService::GET_MEDIA_CATEGORY;
        $this->action->save();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => __('Bo\'limni tanlang'),
            'reply_markup' => json_encode([
                'keyboard' => KeyboardsService::getCategoriesList(MediaTypesConstant::IMAGE),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ])
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getMediaCategory()
    {
        if ($this->updates->callbackQuery()) {
            $this->getMedia();
            die();
        }
        if ($this->text === __(ControlActionsConstant::BACK)) {
            $this->sendMainMenu();
            return;
        }
        $category = Category::query()
            ->where('type', '=', MediaTypesConstant::IMAGE)
            ->where("name_" . app()->getLocale(), '=', $this->text)
            ->first();
        if (!$category) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __("Kategoriyani tanlang"),
                'reply_markup' => json_encode([
                    'keyboard' => KeyboardsService::getCategoriesList(MediaTypesConstant::IMAGE),
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ]);
            return;
        }
        $this->bot_user->category_id = $category->id;
        $this->bot_user->save();
        /** @var Media $media */
        $media = Media::query()
            ->where('status', '=', true)
            ->where('category_id', '=', $category->id)
            ->first();
        if (!$media) {
            $message = $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __("Rasm mavjud emas")
            ]);
            $this->saveMessage($message);
            return;
        }

        $message = $this->telegram->send('sendPhoto', [
            'chat_id' => $this->chat_id,
            'photo' => $media->file_id,
            'reply_markup' => json_encode([
                'inline_keyboard' => KeyboardsService::attachLabel($media)
            ])
        ]);
        $this->saveMessage($message);
    }

    /**
     * @throws RequestException
     */
    public function getMedia()
    {
        $data = $this->updates->getCallbackQueryData();
        $id = explode('_', $data)[1];
        if (!$id) {
            $this->telegram->send('sendMessage', [
                'chat_id' => $this->chat_id,
                'text' => __("Rasm mavjud emas"),
            ]);
            return;
        }
        /** @var Media $media */
        $media = Media::query()->find($id);

        $this->telegram->send('editMessageMedia', [
            'chat_id' => $this->chat_id,
            'message_id' => $this->updates->getMessageId(),
            'media' => json_encode([
                'type' => 'photo',
                'media' => $media->file_id
            ]),
            'reply_markup' => json_encode([
                'inline_keyboard' => KeyboardsService::attachLabel($media)
            ])
        ]);
    }

    private function setMainAction()
    {
        if ($this->action->action !== ActionsService::PHOTO) {
            $this->action->action = ActionsService::PHOTO;
            $this->action->sub_action = SubactionsService::GET_MEDIA_CATEGORY;
            $this->action->save();
        }
    }
}
