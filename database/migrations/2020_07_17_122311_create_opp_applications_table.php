<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOppApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opp_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assign_id');
            $table->integer('user_id');
            $table->string('status',10);
            $table->text('remarks');
            $table->integer('art_id');
            $table->integer('approver_id');

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
        Schema::dropIfExists('opp_applications');
    }
}
