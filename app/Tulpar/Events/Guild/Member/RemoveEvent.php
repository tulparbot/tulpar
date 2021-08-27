<?php

namespace App\Tulpar\Events\Guild\Member;

use App\Models\Goodbye;
use App\Tulpar\Helpers;
use App\Tulpar\Image\LeaveImageGenerator;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\User\Member;

class RemoveEvent
{
    public function __invoke(Member $member, Discord $discord)
    {
        if (!$member->user->bot) {
            $goodbye = Goodbye::where('enable', true)->where('server_id', $member->guild_id)->first();
            if ($goodbye !== null) {
                $builder = MessageBuilder::new();

                if ($goodbye->image_enable) {
                    $generator = new LeaveImageGenerator(
                        $member->user->username . '#' . $member->user->discriminator,
                        $goodbye->text,
                        $member->user->avatar,
                        $goodbye->background_image ?? null,
                        $goodbye->foreground_color ?? '#000000',
                    );
                    $generator->make();
                    $builder->addFile($generator->getCachedFilePath())->setContent(Helpers::userTag($member->user->id));
                }
                else {
                    $builder->setContent(sprintf($goodbye->text ?? 'Goodbye, %s', Helpers::userTag($member->user->id)));
                }

                $member->guild->channels->get('id', $goodbye->channel_id)?->sendMessage($builder);
            }
        }
    }
}
