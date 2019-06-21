<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonSkillsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_skillsets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('category', 1); // (p)redefined by admin or (m)anually added
            $table->string('skillgroup');
            $table->string('name');
            $table->string('skilltype');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('common_skillsets');
    }
}
