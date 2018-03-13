<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->integer('corporation_id')->nullable()->unsigned();
            $table->integer('alliance_id')->nullable()->unsigned();
            $table->integer('faction_id')->nullable()->unsigned();
            $table->string('token',512)->nullable();
            $table->datetime('token_expiry')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
