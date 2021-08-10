<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [
        'executed',
        'guild_id',
        'job',
    ];

    public function run(): void
    {
        $this->update(['executed' => true]);
    }
}
