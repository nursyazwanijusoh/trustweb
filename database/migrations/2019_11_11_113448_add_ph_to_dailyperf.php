<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhToDailyperf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_performances', function (Blueprint $table) {
          $table->boolean('is_public_holiday')->default(false);
          $table->integer('public_holiday_id')->nullable();
          $table->integer('avatar_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dailyperf', function (Blueprint $table) {

        });
    }
}
