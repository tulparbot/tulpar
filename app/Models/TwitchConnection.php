<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwitchConnection extends Model
{
    use HasFactory;

    protected $table = 'twitch_connections';

    protected $fillable = [
        'guild_id',
        'channel_id',
        'user_id',
        'token',
        'accounts',
    ];

    public static function findToken(\Discord\Parts\User\User|string $user): string|null
    {
        if ($user instanceof \Discord\Parts\User\User) {
            $user = $user->id;
        }

        $connection = TwitchConnection::where('user_id', $user)->first();
        if ($connection == null || $connection->token == null) {
            return null;
        }

        return $connection->token;
    }
}
