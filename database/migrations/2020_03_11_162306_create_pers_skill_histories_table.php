<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersSkillHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pers_skill_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('personal_skillset_id');
            $table->integer('action_user_id');
            $table->string('remark', 500)->nullable();
            $table->string('action', 100)->nullable();
            $table->text('extra_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pers_skill_histories');
    }
}
