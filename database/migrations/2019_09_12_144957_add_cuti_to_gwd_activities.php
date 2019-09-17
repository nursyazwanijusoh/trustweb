<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCutiToGwdActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gwd_activities', function (Blueprint $table) {
          $table->boolean('isleave')->default(false);
          $table->string('leave_remark')->nullable();
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
          $table->dropColumn('isleave');
          $table->dropColumn('leave_remark');
        });
    }
}
