<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOppAssigmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opp_assigments', function (Blueprint $table) {
            $table->increments('id');
           
            $table->integer('proj_id');
            $table->integer('user_id');
            $table->string('status',10);
            $table->integer('created_by');
            $table->integer('updated_by');
            
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opp_assigments');
    }
}
