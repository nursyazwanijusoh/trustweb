<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOppProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opp_projects', function (Blueprint $table) {
            
            

            
            

            $table->text('descr')->after('id');

            $table->string('status', 20)->default('Draft')->after('descr');
            $table->double('total_mandays', 7, 2)->after('status');
            $table->integer('created_by')->after('total_mandays');
            $table->integer('lead_by')->after('created_by')->nullable();
            $table->string('parent_id')->after('lead_by')->nullable();
            $table->date('start_date')->after('parent_id');
            $table->date('end_date')->after('start_date');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('opp_projects', function (Blueprint $table) {
            $table->dropColumn('descr');
            $table->dropColumn('status');
            $table->dropColumn('total_mandays');
            $table->dropColumn('created_by');
            $table->dropColumn('lead_by');
            $table->dropColumn('parent_id');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });
    }
}
