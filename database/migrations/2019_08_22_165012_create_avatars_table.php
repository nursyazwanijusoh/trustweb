<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatars', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('rank')->unique();
            $table->decimal('min_hours', 8, 2);
            $table->decimal('max_hours', 8, 2);
            $table->boolean('isexternal')->default(true);
            $table->text('image_url')->nullable();
            $table->string('image_credit')->nullable();
            $table->string('local_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avatars');
    }
}
