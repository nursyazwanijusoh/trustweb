<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcoTravelReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mco_travel_reqs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('request_date')->nullable();
            $table->integer('requestor_id');
            $table->integer('approver_id');  // gm
            $table->string('location', 500);
            $table->text('reason');
            $table->string('status', 100);
            $table->integer('unit_id');
            $table->timestamp('action_datetime')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mco_travel_reqs');
    }
}
