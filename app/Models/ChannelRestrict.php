<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelRestrict extends Model
{
    use HasFactory;

    protected $table = 'channel_restricts';

    protected $fillable = [
        'enable',
        'server_id',
        'channel_id',
        'restrict',
        'message',
        'command_prefixes',
    ];

    public function getCommandPrefixesAttribute(): array
    {
        return @unserialize($this->attributes['command_prefixes']) ?? [];
    }
}
