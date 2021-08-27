<?php

use App\Enums\ChannelRestricts;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelRestrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_restricts', function (Blueprint $table) {
            $table->id();
            $table->boolean('enable')->default(true);
            $table->string('server_id')->nullable()->default(null);
            $table->string('channel_id')->nullable()->default(null);
            $table->string('restrict')->nullable()->default(ChannelRestricts::TextOnly);
            $table->string('message')->nullable()->default(null);
            $table->string('command_prefixes')->nullable()->default(serialize([]));
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
        Schema::dropIfExists('channel_restricts');
    }
}
