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
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('staff_id', 10);
            $table->integer('role')->nullable();
            $table->integer('curr_reserve')->nullable();
            $table->integer('curr_checkin')->nullable();
            $table->integer('last_checkin')->nullable();
            $table->string('mobile_no', 15)->nullable();
            $table->string('photo_url', 255)->nullable();
            $table->string('allowed_building', 255)->nullable();
            $table->string('lob', 20)->nullable();
            $table->integer('status')->nullable();
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
