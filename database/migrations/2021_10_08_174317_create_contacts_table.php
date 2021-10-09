<?php

use App\Models\Contact;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->mediumText('value')->nullable();
            $table->timestamps();
        });

        Contact::query()->insert([
            [
                'name' => 'phone',
                'value' => '+998931588585'
            ],
            [
                'name' => 'instagram',
                'value' => 'https://www.instagram.com/firdavsibodullaev/',
            ],
            [
                'name' => 'telegram',
                'value' => 'https://t.me/UserFS',
            ],
            [
                'name' => 'location',
                'value' => json_encode([
                    "latitude" => 40.107456,
                    "longitude" => 65.374427
                ]),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
