<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelcomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welcomers', function (Blueprint $table) {
            $table->id();
            $table->string('server_id')->nullable()->default(null);
            $table->string('channel_id')->nullable()->default(null);
            $table->boolean('enable')->default(false);
            $table->boolean('image_enable')->default(false);
            $table->string('text')->nullable()->default('Welcome, %s');
            $table->string('background_image')->nullable()->default(null);
            $table->string('foreground_color')->nullable()->default('#000000');
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
        Schema::dropIfExists('welcomers');
    }
}
