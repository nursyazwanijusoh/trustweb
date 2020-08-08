<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraToStaffLeaves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_leaves', function (Blueprint $table) {
          $table->integer('created_by')->nullable();
          $table->boolean('is_manual')->default(false);
          $table->text('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_leaves', function (Blueprint $table) {
          $table->dropColumn('created_by');
          $table->dropColumn('is_manual');
          $table->dropColumn('remark');
        });
    }
}
