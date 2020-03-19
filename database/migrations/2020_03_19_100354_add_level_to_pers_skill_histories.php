<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLevelToPersSkillHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pers_skill_histories', function (Blueprint $table) {
          $table->integer('newlevel')->default(0);
          $table->integer('oldlevel')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pers_skill_histories', function (Blueprint $table) {
          $table->dropColumn('newlevel');
          $table->dropColumn('oldlevel');
        });
    }
}
