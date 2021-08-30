<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeratorRole extends Model
{
    use HasFactory;

    protected $table = 'moderator_roles';

    protected $fillable = [
        'server_id',
        'role_id',
    ];
}
