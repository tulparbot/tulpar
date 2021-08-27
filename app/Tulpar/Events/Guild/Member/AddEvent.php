<?php

namespace App\Tulpar\Events\Guild\Member;

use App\Models\Welcomer;
use App\Tulpar\Helpers;
use App\Tulpar\Image\WelcomeImageGenerator;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\User\Member;

class AddEvent
{
    public function __invoke(Member $member, Discord $discord)
    {
        if (!$member->user->bot) {
            $welcomer = Welcomer::where('enable', true)->where('server_id', $member->guild_id)->first();
            if ($welcomer !== null) {
                $builder = MessageBuilder::new();

                if ($welcomer->image_enable) {
                    $generator = new WelcomeImageGenerator(
                        $member->user->username . '#' . $member->user->discriminator,
                        $welcomer->text,
                        $member->user->avatar,
                        $welcomer->background_image ?? null,
                        $welcomer->foreground_color ?? '#000000',
                    );
                    $generator->make();
                    $builder->addFile($generator->getCachedFilePath())->setContent(Helpers::userTag($member->user->id));
                }
                else {
                    $builder->setContent(sprintf($welcomer->text ?? 'Welcome, %s', Helpers::userTag($member->user->id)));
                }

                $member->guild->channels->get('id', $welcomer->channel_id)?->sendMessage($builder);
            }
        }
    }
}
