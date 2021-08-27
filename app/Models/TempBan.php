<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempBan extends Model
{
    use HasFactory;

    protected $table = 'temp_bans';

    protected $fillable = [
        'server_id',
        'member_id',
        'reason',
        'end_at',
    ];
}
