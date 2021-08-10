<?php

namespace App\Models;

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
    ];

    protected $appends = [
        'xp',
        'level',
    ];

    public function getXpAttribute(): int
    {
        return $this->getAttribute('message_count') * 0.5;
    }

    public function getLevelAttribute(): int
    {
        $level = $this->getXpAttribute() / 100;
        return $level < 0 ? 0 : $level;
    }

    public static function find(string $guild_id, string $user_id): static
    {
        $userRank = UserRank::where('guild_id', $guild_id)->where('user_id', $user_id)->first();
        if ($userRank == null) {
            $userRank = UserRank::create([
                'guild_id' => $guild_id,
                'user_id' => $user_id,
                'message_count' => 0,
            ]);
        }

        return $userRank;
    }
}
