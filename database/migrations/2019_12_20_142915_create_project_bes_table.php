<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectBesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_bes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('lead_by');
            $table->string('parent_number');
            $table->text('project descr');
            $table->double('total_mandays', 7, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 50)->default('Draft');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_bes');
    }
}
