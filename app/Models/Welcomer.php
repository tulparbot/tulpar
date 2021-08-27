<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Welcomer extends Model
{
    use HasFactory;

    protected $table = 'welcomers';

    protected $fillable = [
        'server_id',
        'channel_id',
        'enable',
        'image_enable',
        'text',
        'background_image',
        'foreground_color',
    ];
}
