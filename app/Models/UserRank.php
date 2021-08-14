<?php

namespace App\Models;

use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    use HasFactory;

    protected $table = 'user_ranks';

    protected $fillable = [
        'guild_id',
        'user_id',
        'message_count',
        'message_count_guilds',
        'message_count_channels',
    ];

    protected $appends = [
        'xp',
        'level',
    ];

    public function getGuildMessageCount(Guild|string $guild): int
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        $guilds = @unserialize($this->message_count_guilds) ?? [];
        if (isset($guilds[$guild])) {
            return $guilds[$guild];
        }

        return 0;
    }

    public function getChannelMessageCount(Channel|string $channel): int
    {
        if ($channel instanceof Channel) {
            $channel = $channel->id;
        }

        $channels = @unserialize($this->message_count_channels) ?? [];
        if (isset($channels[$channel])) {
            return $channels[$channel];
        }

        return 0;
    }

    public function incrementGuildMessages(Guild|string $guild): static
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        $messages = @unserialize($this->message_count_guilds) ?? [];
        $messages[$guild] = $this->getGuildMessageCount($guild) + 1;
        $this->message_count_guilds = @serialize($messages);
        return $this;
    }

    public function incrementChannelMessages(Channel|string $channel): static
    {
        if ($channel instanceof Channel) {
            $channel = $channel->id;
        }

        $messages = @unserialize($this->message_count_channels) ?? [];
        $messages[$channel] = $this->getChannelMessageCount($channel) + 1;
        $this->message_count_channels = @serialize($messages);
        return $this;
    }

    public function getXpAttribute(): int
    {
        return $this->getAttribute('message_count') * 0.3;
    }

    public function getLevelAttribute(): int
    {
        $level = $this->getXpAttribute() / 100;
        return $level < 0 ? 0 : $level;
    }

    public static function find(Guild|string $guild, \Discord\Parts\User\User|string $user): static
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if ($user instanceof \Discord\Parts\User\User) {
            $user = $user->id;
        }

        $userRank = UserRank::where('guild_id', $guild)->where('user_id', $user)->first();
        if ($userRank == null) {
            $userRank = UserRank::create([
                'guild_id' => $guild,
                'user_id' => $user,
                'message_count' => 0,
            ]);
        }

        return $userRank;
    }
}
