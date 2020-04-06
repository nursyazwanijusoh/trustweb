<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkSkillsetAddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_skillset_adds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('staff_no', 50);
            $table->string('category', 100);
            $table->string('type', 100);
            $table->string('skill', 200);
            $table->integer('level');
            $table->string('remark', 200);
            $table->string('load_status', 1)->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulk_skillset_adds');
    }
}
