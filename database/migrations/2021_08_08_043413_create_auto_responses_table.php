<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_responses', function (Blueprint $table) {
            $table->id();
            $table->string('guild_id')->nullable()->default(null);
            $table->string('message')->nullable()->default(null);
            $table->string('reply')->nullable()->default(null);
            $table->string('emoji')->nullable()->default(null);
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
        Schema::dropIfExists('auto_responses');
    }
}
