<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_id')->nullable()->default(null);

            $table->string('name')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->string('region')->nullable()->default(null);
            $table->string('preferred_locale')->nullable()->default(null);
            $table->string('features')->nullable()->default(serialize([]));
            $table->boolean('large')->nullable()->default(false);
            $table->integer('verification_level')->nullable()->default(null);
            $table->integer('premium_tier')->nullable()->default(null);
            $table->integer('premium_subscription_count')->nullable()->default(null);
            $table->integer('member_count')->nullable()->default(null);
            $table->integer('max_members')->nullable()->default(null);
            $table->integer('max_video_channel_users')->nullable()->default(null);

            $table->string('owner_id')->nullable()->default(null);
            $table->string('application_id')->nullable()->default(null);
            $table->string('system_channel_id')->nullable()->default(null);
            $table->string('rules_channel_id')->nullable()->default(null);
            $table->string('public_updates_channel_id')->nullable()->default(null);

            $table->string('roles')->nullable()->default(null);
            $table->string('channels')->nullable()->default(null);
            $table->string('members')->nullable()->default(null);
            $table->string('administrators')->nullable()->default(null);
            $table->string('invites')->nullable()->default(null);
            $table->string('bans')->nullable()->default(null);
            $table->string('emojis')->nullable()->default(null);

            $table->timestamp('joined_at')->nullable()->default(null);
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
        Schema::dropIfExists('servers');
    }
}
