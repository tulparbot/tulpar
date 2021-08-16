<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitchConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitch_connections', function (Blueprint $table) {
            $table->id();
            $table->string('guild_id')->nullable()->default(null);
            $table->string('channel_id')->nullable()->default(null);
            $table->string('user_id')->nullable()->default(null);
            $table->string('token')->nullable()->default(null);
            $table->string('accounts')->nullable()->default(serialize([]));
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
        Schema::dropIfExists('twitch_connections');
    }
}
