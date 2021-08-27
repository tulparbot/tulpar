<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempBansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_bans', function (Blueprint $table) {
            $table->id();
            $table->string('server_id')->nullable()->default(null);
            $table->string('member_id')->nullable()->default(null);
            $table->string('reason')->nullable()->default(null);
            $table->timestamp('end_at')->nullable()->default(null);
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
        Schema::dropIfExists('temp_bans');
    }
}
