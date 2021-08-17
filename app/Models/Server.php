<?php

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

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
        'log_channel',
    ];

    protected $dates = [
        'joined_at',
    ];

    protected $appends = [
        'short_name',
        'data',
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
        return Cache::remember('server-' . $this->id . '-roles', Carbon::make('+6 hours'), function () {
            $client = new Client();
            $r = $client->get('https://discord.com/api/v9/guilds/' . $this->server_id . '/roles', [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bot ' . config('discord.token'),
                ],
            ]);
            return json_decode($r->getBody()->getContents());
        });
    }

    public function getChannelsAttribute()
    {
        return @json_decode($this->attributes['channels']) ?? null;
    }

    public function getMembersAttribute()
    {
        return Cache::remember('server-' . $this->id . '-members', Carbon::make('+6 hours'), function () {
            $client = new Client();
            $r = $client->get('https://discord.com/api/v9/guilds/' . $this->server_id . '/members?limit=1000', [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bot ' . config('discord.token'),
                ],
            ]);
            return json_decode($r->getBody()->getContents());
        });
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

    public function getDataAttribute(): object|null
    {
        $data = null;
        if (auth()->check() && auth()->id() == $this->id) {
            foreach (auth()->user()->servers as $server) {
                if ($server->id == $this->server_id) {
                    $data = $server;
                }
            }
        }

        return $data;
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'uid', 'owner_id');
    }
}
