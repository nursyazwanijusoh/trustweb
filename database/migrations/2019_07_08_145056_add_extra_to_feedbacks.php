<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraToFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
          $table->text('remark')->nullable();
          $table->integer('closed_by')->nullable();
          $table->boolean('contacted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
          $table->dropColumn('remark');
          $table->dropColumn('closed_by');
          $table->dropColumn('contacted');
        });
    }
}
