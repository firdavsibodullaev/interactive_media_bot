<?php

namespace App\Services\TelegramServices;

use App\Models\Contact;

/**
 * Class ContactService
 * @package App\Services\TelegramServices
 */
class ContactService extends TelegramService
{
    public function index()
    {
        $contact = Contact::query()->get();

        $this->telegram->send('sendMessage', [
            'chat_id' => $this->chat_id,
            'text' => $this->getText($contact),
            'parse_mode' => 'html',
        ]);

        $coordinates = json_decode($contact->where('name', 'location')->first()->value, true);
        $this->telegram->send('sendLocation', [
            'chat_id' => $this->chat_id,
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);

    }


    public function getText($contact): string
    {
        return "<strong>" . __("Bizning ma'lumotlar") . "</strong>"
            . PHP_EOL . PHP_EOL . "<strong>" . __('Kontakt') . ":</strong> {$contact->where('name', 'phone')->first()->value}"
            . PHP_EOL . "<strong>" . __("Telegram") . ":</strong> <a href='{$contact->where('name', 'telegram')->first()->value}'>Havola</a>"
            . PHP_EOL . "<strong>" . __("Instagram") . ":</strong> <a href='{$contact->where('name', 'instagram')->first()->value}'>Havola</a>";
    }
}
