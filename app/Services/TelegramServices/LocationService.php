<?php

namespace App\Services\TelegramServices;

use App\Models\Contact;
use App\Models\Message;
use Illuminate\Http\Client\RequestException;

/**
 * Class ContactService
 * @package App\Services\TelegramServices
 */
class LocationService extends TelegramService
{
    public function index()
    {
        $contact = Contact::query()->get();

        $coordinates = json_decode($contact->where('name', 'location')->first()->value, true);
        $message = $this->telegram->send('sendLocation', [
            'chat_id' => $this->chat_id,
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);
        $this->saveMessage($message);
    }

}
