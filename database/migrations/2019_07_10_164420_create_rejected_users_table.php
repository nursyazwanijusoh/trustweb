<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRejectedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejected_users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('staff_no', 10);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no', 15)->nullable();
            $table->string('action', 10);
            $table->integer('partner_id');
            $table->text('remark')->nullable();
            $table->integer('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rejected_users');
    }
}
