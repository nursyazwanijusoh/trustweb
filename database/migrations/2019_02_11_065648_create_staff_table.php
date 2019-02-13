<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('staff_no', 10);
            $table->integer('role');
            $table->integer('curr_reserve');
            $table->integer('curr_checkin');
            $table->integer('last_checkin');
            $table->string('name', 50);
            $table->string('mobile_no', 15);
            $table->string('photo_url', 255);
            $table->string('allowed_building', 255);
            $table->integer('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
