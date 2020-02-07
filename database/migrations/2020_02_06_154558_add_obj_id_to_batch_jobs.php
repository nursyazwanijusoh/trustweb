<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObjIdToBatchJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_jobs', function (Blueprint $table) {
          $table->string('class_name')->nullable();
          $table->integer('obj_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_jobs', function (Blueprint $table) {
          $table->dropColumn('class_name');
          $table->dropColumn('obj_id');
        });
    }
}
