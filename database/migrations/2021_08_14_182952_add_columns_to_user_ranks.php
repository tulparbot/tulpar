<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserRanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_ranks', function (Blueprint $table) {
            $table->string('message_count_guilds')->nullable()->default(serialize([]));
            $table->string('message_count_channels')->nullable()->default(serialize([]));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_ranks', function (Blueprint $table) {
            $table->dropColumn(['message_count_guilds', 'message_count_channels']);
        });
    }
}
