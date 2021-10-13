<?php

use App\Constants\MediaTypesConstant;
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz')->nullable();
            $table->string('name_ru')->nullable();
            $table->enum('type', [MediaTypesConstant::list()]);
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });

        Category::query()->insert([
            [
                'name_uz' => 'Nikoh to\'yidan lavhalar',
                'name_ru' => 'Отрывки из свадеб',
                'type' => MediaTypesConstant::VIDEO,
            ],
            [
                'name_uz' => 'Sunnat to\'yidan lavhalar',
                'name_ru' => 'Отрывки из суннат туйи',
                'type' => MediaTypesConstant::VIDEO,
            ],
            [
                'name_uz' => 'Tug\'riqxonadan bola olib chiqishdan lavhalar',
                'name_ru' => 'Отрывки из роддома',
                'type' => MediaTypesConstant::VIDEO,
            ],
            [
                'name_uz' => 'Kortej',
                'name_ru' => 'Кортеж',
                'type' => MediaTypesConstant::VIDEO,
            ],
            [
                'name_uz' => 'Original lavhalar',
                'name_ru' => 'Оригинальные кадры',
                'type' => MediaTypesConstant::VIDEO,
            ],
            [
                'name_uz' => 'Nikoh to\'yi albomlari',
                'name_ru' => 'Альбом из свадеб',
                'type' => MediaTypesConstant::IMAGE,
            ],
            [
                'name_uz' => 'Sunnat to\'yi albomlari',
                'name_ru' => 'Альбомы из суннат туйи',
                'type' => MediaTypesConstant::IMAGE,
            ],
            [
                'name_uz' => 'Tug\'riqxonadan bola olib chiqishdan albomlari',
                'name_ru' => 'Альбомы из роддома',
                'type' => MediaTypesConstant::IMAGE,
            ],
            [
                'name_uz' => 'Montajsiz rasmlar',
                'name_ru' => 'Фотографии без монтажа',
                'type' => MediaTypesConstant::IMAGE,
            ],
            [
                'name_uz' => 'Tasodifiy rasmlar',
                'name_ru' => 'Случайные фотографии',
                'type' => MediaTypesConstant::IMAGE,
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
        Schema::dropIfExists('categories');
    }
}
