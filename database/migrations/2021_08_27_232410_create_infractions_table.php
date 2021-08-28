<?php

use App\Enums\InfractionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infractions', function (Blueprint $table) {
            $table->id();
            $table->string('server_id')->nullable()->default(null);
            $table->string('member_id')->nullable()->default(null);
            $table->string('owner_id')->nullable()->default(null);
            $table->string('type')->default(InfractionType::Custom);
            $table->string('reason')->nullable()->default(null);
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
        Schema::dropIfExists('infractions');
    }
}
