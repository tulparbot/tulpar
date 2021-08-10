<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoResponse extends Model
{
    use HasFactory;

    protected $table = 'auto_responses';

    protected $fillable = [
        'guild_id',
        'message',
        'reply',
        'emoji',
    ];
}
