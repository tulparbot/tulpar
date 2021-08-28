<?php

namespace App\Models;

use App\Enums\InfractionType;
use App\Tulpar\Tulpar;
use Discord\Exceptions\IntentException;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infraction extends Model
{
    use HasFactory;

    protected $table = 'infractions';

    protected $fillable = [
        'server_id',
        'member_id',
        'owner_id',
        'type',
        'reason',
    ];

    /**
     * @param Guild|string  $guild
     * @param Member|string $member
     * @return string
     */
    public static function getRecordsString(Guild|string $guild, Member|string $member): string
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if ($member instanceof Member) {
            $member = $member->id;
        }

        $infractions = Infraction::where('server_id', $guild)->where('member_id', $member)->get();

        $others = 0;
        $hardBans = 0;
        $mutes = 0;
        $kicks = 0;
        $bans = 0;
        $tempBans = 0;

        foreach ($infractions as $infraction) {
            if ($infraction->type == InfractionType::Custom) {
                $others++;
            }
            else if ($infraction->type == InfractionType::TempBan) {
                $tempBans++;
            }
            else if ($infraction->type == InfractionType::Ban) {
                $bans++;
            }
            else if ($infraction->type == InfractionType::Kick) {
                $kicks++;
            }
            else if ($infraction->type == InfractionType::Mute) {
                $mutes++;
            }
            else if ($infraction->type == InfractionType::HardBan) {
                $hardBans++;
            }
        }

        $strings = [];

        if ($hardBans > 0) {
            $strings[] = $hardBans . ' HARD Bans';
        }

        if ($bans > 0) {
            $strings[] = $bans . ' Bans';
        }

        if ($tempBans > 0) {
            $strings[] = $tempBans . ' Temporary Bans';
        }

        if ($kicks > 0) {
            $strings[] = $kicks . ' Kicks';
        }

        if ($mutes > 0) {
            $strings[] = $mutes . ' Mutes';
        }

        if ($others > 0) {
            $strings[] = $others . ' Warnings';
        }

        if (count($strings) > 0) {
            return implode(', ', $strings) . ' found.';
        }

        return 'No any bans, warnings mutes or kicks found.';
    }

    /**
     * @param Guild|string       $guild
     * @param Member|string      $member
     * @param string             $type
     * @param string|null        $reason
     * @param Member|string|null $owner
     * @return mixed
     * @throws IntentException
     */
    public static function make(
        Guild|string       $guild,
        Member|string      $member,
        string             $type = InfractionType::Custom,
        string|null        $reason = null,
        Member|string|null $owner = null,
    ): mixed
    {
        if ($guild instanceof Guild) {
            $guild = $guild->id;
        }

        if ($member instanceof Member) {
            $member = $member->id;
        }

        if ($owner instanceof Member) {
            $owner = $owner->id;
        }
        else if ($owner === null) {
            $owner = Tulpar::getInstance()->getDiscord()->user->id;
        }

        return Infraction::create([
            'server_id' => $guild,
            'member_id' => $member,
            'owner_id' => $owner,
            'type' => $type,
            'reason' => $reason,
        ]);
    }
}
