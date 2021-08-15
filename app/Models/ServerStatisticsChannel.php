<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerStatisticsChannel extends Model
{
    use HasFactory;

    protected $table = 'server_statistics_channels';

    protected $fillable = [
        'guild_id',
        'channel_id',
        'type',
    ];
}
