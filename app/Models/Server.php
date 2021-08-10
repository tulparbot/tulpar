<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Server extends Model
{
    use HasFactory;

    protected $table = 'servers';

    protected $fillable = [
        'server_id',
        'name',
        'icon',
        'description',
        'region',
        'preferred_locale',
        'features',
        'large',
        'verification_level',
        'premium_level',
        'premium_subscription_count',
        'member_count',
        'max_members',
        'max_video_channel_users',
        'owner_id',
        'application_id',
        'system_channel_id',
        'rules_channel_id',
        'public_updates_channel_id',
        'roles',
        'channels',
        'members',
        'administrators',
        'invites',
        'bans',
        'emojis',
        'joined_at',
    ];

    protected $dates = [
        'joined_at',
    ];

    protected $appends = [
        'short_name'
    ];

    public function getShortNameAttribute(): string
    {
        $words = explode(' ', $this->name);
        $name = '';

        foreach ($words as $word) {
            $name .= $word[0];
        }

        return mb_strtoupper($name);
    }

    public function getFeaturesAttribute()
    {
        return @unserialize($this->attributes['features']) ?? null;
    }

    public function getRolesAttribute()
    {
        return @json_decode($this->attributes['roles']) ?? null;
    }

    public function getChannelsAttribute()
    {
        return @json_decode($this->attributes['channels']) ?? null;
    }

    public function getMembersAttribute()
    {
        return @json_decode($this->attributes['members']) ?? null;
    }

    public function getInvitesAttribute()
    {
        return @json_decode($this->attributes['invites']) ?? null;
    }

    public function getBansAttribute()
    {
        return @json_decode($this->attributes['bans']) ?? null;
    }

    public function getEmojisAttribute()
    {
        return @json_decode($this->attributes['emojis']) ?? null;
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'uid', 'owner_id');
    }
}
