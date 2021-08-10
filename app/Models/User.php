<?php

namespace App\Models;

use App\Tulpar\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'nickname',
        'name',
        'email',
        'avatar',
        'data',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var string[] $appends
     */
    protected $appends = [
        'servers',
    ];

    /**
     * @return array
     */
    public function getDataAttribute(): array
    {
        return @unserialize($this->attributes['data']) ?? [];
    }

    /**
     * @return Collection
     */
    public function getServersAttribute(): Collection
    {
        return collect(Helpers::getUserGuilds());
    }

    /**
     * @return HasMany
     */
    public function joinedServers(): HasMany
    {
        return $this->hasMany(Server::class, 'owner_id', 'uid');
    }
}
