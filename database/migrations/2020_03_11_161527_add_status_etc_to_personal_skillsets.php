<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusEtcToPersonalSkillsets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_skillsets', function (Blueprint $table) {
          $table->string('status', 2)->default('N');
          $table->integer('prev_level')->default(0);
        });

        Schema::table('common_skillsets', function (Blueprint $table) {
            $table->integer('skill_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_skillsets', function (Blueprint $table) {
          $table->dropColumn('status');
          $table->dropColumn('prev_level');
        });

        Schema::table('common_skillsets', function (Blueprint $table) {
          $table->dropColumn('skill_type_id');
        });
    }
}
