<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyperfToGwdActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gwd_activities', function (Blueprint $table) {
          $table->integer('daily_performance_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gwd_activities', function (Blueprint $table) {
          $table->dropColumn('daily_performance_id');
        });
    }
}
