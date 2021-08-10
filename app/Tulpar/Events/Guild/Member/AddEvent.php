<?php

namespace App\Tulpar\Events\Guild\Member;

use Discord\Discord;
use Discord\Parts\User\Member;
use Illuminate\Support\Str;
use Intervention\Image\Gd\Font;
use Intervention\Image\ImageManager;

class AddEvent
{
    public function __invoke(Member $member, Discord $discord)
    {
        $manager = new ImageManager([]);

        $avatar_path = storage_path('/tmp/' . Str::random() . '.png');
        $filepath = storage_path('/tmp/' . Str::random() . '.png');
        file_put_contents($avatar_path, file_get_contents($member->user->avatar));

        $image = $manager->make($avatar_path);
        $image->encode('png');
        $image->fit(120, 120);
        $width = $image->getWidth();
        $height = $image->getHeight();
        $mask = $manager->canvas($width, $height);
        $mask->circle($width, $width / 2, $height / 2, function ($draw) {
            $draw->background('#fff');
        });
        $image->mask($mask, false);
        $image->save($avatar_path . '.circle.png');

        $image = $manager->canvas(500, 260);
        $image->fill('#ffffff');

        $image->text('Welcome', 215, (260 / 2) - 13, function ($font) {
            /** @var Font $font */
            $font->file(storage_path('/fonts/Montserrat-Light.ttf'));
            $font->size(18);
            $font->color('#ff0000');
            $font->align('center');
            $font->valign('middle');
        });
        $image->text($member->username, 220, (260 / 2) + 13, function ($font) {
            /** @var Font $font */
            $font->file(storage_path('/fonts/Montserrat-SemiBold.ttf'));
            $font->size(26);
            $font->color('#ff0000');
            $font->align('center');
            $font->valign('middle');
        });

        $image->insert($avatar_path . '.circle.png', 'left', 25, ((260 / 2) - 120));
        $image->save($filepath);

        $member->guild->channels->first()->sendFile($filepath);
    }
}
