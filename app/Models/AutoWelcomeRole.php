<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoWelcomeRole extends Model
{
    use HasFactory;

    protected $table = 'auto_welcome_roles';

    protected $fillable = [
        'enable',
        'server_id',
        'role_id',
        'message',
    ];
}
