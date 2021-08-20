<?php

namespace App\Tulpar\Events\Guild\Member;

use App\Tulpar\Image\WelcomeImageGenerator;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\User\Member;

class AddEvent
{
    public function __invoke(Member $member, Discord $discord)
    {
        $generator = new WelcomeImageGenerator(
            $member->user->username,
            'Welcome,',
            $member->user->avatar,
            null,
            '#000000',
        );
        $generator->make();
        $builder = MessageBuilder::new()->addFile($generator->getCachedFilePath());
        $member->guild->channels->first()->sendMessage($builder);
    }
}
