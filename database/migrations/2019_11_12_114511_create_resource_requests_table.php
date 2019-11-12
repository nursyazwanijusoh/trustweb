<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('request_by');
            $table->integer('request_to');
            $table->string('resource_model');
            $table->integer('resource_id');
            $table->timestamp('request_time')->nullable();
            $table->timestamp('response_time')->nullable();
            $table->string('status')->default('req_sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_requests');
    }
}
