<?php

use App\Constants\UserRoleConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->string('first_name');
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('lang')->default('uz');
            $table->enum('role', UserRoleConstant::list())->default(UserRoleConstant::CLIENT);
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_users');
    }
}
