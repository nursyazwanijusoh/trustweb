<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersnoToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->integer('persno')->nullable();
          $table->integer('report_to')->nullable();
          $table->string('position', 500)->nullable();
          $table->string('cost_center', 10)->nullable();
          $table->integer('division_id')->nullable();
          $table->integer('section_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->dropColumn('persno');
          $table->dropColumn('report_to');
        });
    }
}
