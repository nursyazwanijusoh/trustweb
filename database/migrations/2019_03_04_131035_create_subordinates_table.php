<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subordinates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('superior_id');
            $table->integer('subordinate_id')->nullable();
            $table->string('sub_name');
            $table->string('sub_staff_no');
            $table->string('sub_post')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subordinates');
    }
}
