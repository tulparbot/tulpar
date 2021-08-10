<?php

namespace App\Models;

use Discord\Parts\Guild\Guild;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Level extends Model
{
    use HasFactory;

    protected $table = 'levels';

    protected $fillable = [
        'guild_id',
        'level',
        'name',
    ];

    /**
     * @param Guild|string $guild
     * @return Collection
     */
    public static function levels(Guild|string $guild): Collection
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        return Cache::remember('levels-' . $guild, Carbon::make('+1 day'), function () use ($guild) {
            return Level::where('guild_id', $guild)->orderBy('level')->get();
        });
    }

    /**
     * @param Guild|string $guild
     * @param int          $rank
     * @return Level
     */
    public static function findLevel(Guild|string $guild, int $rank): Level
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        $levels = static::levels($guild);
        $_ = $levels->first();

        $index = 0;
        foreach ($levels as $level) {
            $levelRank = $level->level * 1000;
            if ($levelRank < $rank) {
                $_ = $level;
            }

            if (isset($levels[$index + 1])) {
                $levelRank = $levels[$index + 1]->level * 1000;
                if ($levelRank > $rank) {
                    break;
                }
            }

            $index++;
        }

        return $_;
    }
}
