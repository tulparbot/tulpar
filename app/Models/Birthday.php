<?php

namespace App\Models;

use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Birthday extends Model
{
    use HasFactory;

    protected $table = 'birthdays';

    protected $fillable = [
        'server_id',
        'member_id',
        'day',
        'month',
        'year',
    ];

    /**
     * @param Guild|string  $guild
     * @param Member|string $member
     * @param int           $day
     * @param int           $month
     * @param int           $year
     * @return bool
     */
    public static function make(Guild|string $guild, Member|string $member, int $day, int $month, int $year): bool
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if ($member instanceof Member) {
            $member = $member->id;
        }

        if (!checkdate($month, $day, $year)) {
            return false;
        }

        $birthday = Birthday::where('server_id', $guild)->where('member_id', $member)->first();
        $birthday?->delete();

        Birthday::create([
            'server_id' => $guild,
            'member_id' => $member,
            'day' => $day,
            'month' => $month,
            'year' => $year,
        ]);

        return true;
    }
}
