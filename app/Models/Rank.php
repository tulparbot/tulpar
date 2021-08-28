<?php

namespace App\Models;

use App\Enums\Align;
use App\Tulpar\Guard;
use App\Tulpar\Helpers;
use App\Tulpar\Image\RankCardGenerator;
use App\Tulpar\Tulpar;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;

    protected $table = 'ranks';

    protected $fillable = [
        'guild_id',
        'member_id',
        'rank',
    ];

    public static function findNextLevelXp(int $xp): int
    {
        return ceil($xp / 100) * 100;
    }

    public static function make(Guild|string $guild, Member|string $member, bool $image = true, bool $embed = true): MessageBuilder
    {
        if (!$guild instanceof Guild) {
            $guild = Tulpar::getInstance()->getDiscord()->guilds->get('id', $guild);
        }

        if (!$member instanceof Member) {
            $member = $guild->members->get('id', $member);
        }

        $userRank = UserRank::find($guild->id, $member->user->id);
        $message = MessageBuilder::new();
        $username = $member->user->username . '#' . $member->user->discriminator;
        $level = $userRank->level;
        $rank = Guard::isRoot($member) ? 'R00T' : 'MEMBER';
        $xp = $userRank->xp;
        $foreground_color = '#ff00ff';
        $avatar_image_url = $member->user->avatar;
        $message_count = $userRank->message_count;
        $hash = md5($username . $level . $rank . $xp . $foreground_color . $avatar_image_url);

        if ($image) {
            $image_path = storage_path('tmp/' . $hash . '.png');
            if (!file_exists($image_path)) {
                $image = new RankCardGenerator;
                $image->attributes['username'] = $username;
                $image->attributes['level'] = 'LVL' . $level;
                $image->attributes['rank'] = $rank;
                $image->attributes['xp'] = $xp . ' / ' . static::findNextLevelXp($xp);
                $image->attributes['foreground_color'] = $foreground_color;
                $image->attributes['avatar_image_url'] = $avatar_image_url;

                $percentage = 0;
                if (strlen($xp) < 3) {
                    $percentage = $xp;
                }
                else {
                    $percentage = substr($xp, 1);
                }

                $image->attributes['percentage'] = $percentage;

                $image->make()->save($image_path);
            }
            $message->addFile($image_path);
        }

        if ($embed) {
            $embed = new Embed(Tulpar::getInstance()->getDiscord());
            $embed->title = $member->nick ?? $username;
            $embed->setThumbnail($avatar_image_url);
            $embed->addFieldValues('Level', '``' . Helpers::line($level, Align::Center, 7) . '``', true);
            $embed->addFieldValues('XP', '``' . Helpers::line($xp, Align::Center, 7) . '``', true);
            $embed->addFieldValues('Total Messages', '``' . Helpers::line($message_count, Align::Center, 7) . '``', true);
            $embed->addFieldValues('Since', $userRank->created_at);
            $embed->addFieldValues('Records', Infraction::getRecordsString($guild, $member));
            $message->addEmbed($embed);
        }

        return $message;
    }
}
