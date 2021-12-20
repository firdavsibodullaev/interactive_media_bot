<?php

namespace App\Services\TelegramServices;

use App\Models\Contact;
use Illuminate\Http\Client\RequestException;

/**
 * Class ContactService
 * @package App\Services\TelegramServices
 */
class ContactService extends TelegramService
{
    /**
     * @throws RequestException
     */
    public function index()
    {
        $contact = Contact::query()->get();

        $message = $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => $this->getText($contact),
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
        ]);

        $this->saveMessage($message);

    }


    public function getText($contact): string
    {
        return "<strong>" . __("Bizning ma'lumotlar") . "</strong>"
            . PHP_EOL . PHP_EOL . "<strong>" . __('Kontakt') . ":</strong> +{$contact->where('name', 'phone')->first()->value}"
            . PHP_EOL . "<strong>" . __("Telegram") . ":</strong> <a href='{$contact->where('name', 'telegram')->first()->value}'>".__('Havola')."</a>"
            . PHP_EOL . "<strong>" . __("Instagram") . ":</strong> <a href='{$contact->where('name', 'instagram')->first()->value}'>".__('Havola')."</a>";
    }
}
