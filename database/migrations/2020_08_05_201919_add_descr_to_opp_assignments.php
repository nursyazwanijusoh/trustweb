<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescrToOppAssignments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opp_assigments', function (Blueprint $table) {
            $table->string('title',255)->after('user_id');
            $table->text('descr')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opp_assigments', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('descr');
        });
    }
}
