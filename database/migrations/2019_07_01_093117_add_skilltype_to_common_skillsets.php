<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkilltypeToCommonSkillsets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_skillsets', function (Blueprint $table) {
            $table->integer('skill_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('common_skillsets', function (Blueprint $table) {
            $table->dropColumn('skill_category_id');
        });
    }
}
