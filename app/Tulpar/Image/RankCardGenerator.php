<?php

namespace App\Tulpar\Image;

use App\Support\Str;
use Intervention\Image\Gd\Font;
use Intervention\Image\Gd\Shapes\RectangleShape;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Spatie\Color\Hex;
use Spatie\Color\Rgb;

class RankCardGenerator extends BaseGenerator
{
    public array $attributes = [
        'background_image_url' => null,
        'avatar_image_url' => null,

        'username' => 'null',
        'rank' => 'null',
        'level' => 'null',
        'xp' => 'null',
        'percentage' => null,

        'background_color' => '#23272A',
        'card_background_color' => '#000000',
        'card_opacity' => 0.6,
        'foreground_color' => '#1abc9c',
        'text_color' => '#ffffff',
    ];

    /**
     * @param string|null $image
     * @return string
     */
    public function getDominantColor(string|null $image): string
    {
        if ($image == null) {
            return $this->attributes['foreground_color'];
        }

        $image = imagecreatefromstring(file_get_contents($image));

        $rTotal = 0;
        $gTotal = 0;
        $bTotal = 0;
        $total = 0;

        for ($x = 0; $x < imagesx($image); $x++) {
            for ($y = 0; $y < imagesy($image); $y++) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $rTotal += $r;
                $gTotal += $g;
                $bTotal += $b;
                $total++;
            }
        }

        $red = round($rTotal / $total);
        $green = round($gTotal / $total);
        $blue = round($bTotal / $total);

        $red += 50;
        if ($red > 255) {
            $red = 255;
        }

        $green += 50;
        if ($green > 255) {
            $green = 255;
        }

        $blue += 50;
        if ($blue > 255) {
            $blue = 255;
        }

        return (new Rgb($red, $green, $blue))->toHex()->__toString();
    }

    /**
     * @param string $url
     * @return string
     */
    public function makeAvatar(string $url): string
    {
        $path = storage_path('/tmp/' . Str::random() . '.png');
        file_put_contents($path, file_get_contents($url));

        $image = $this->getImageManager()->make($path)
            ->encode('png')
            ->fit(80, 80);

        $width = $image->getWidth();
        $height = $image->getHeight();

        $mask = $this->getImageManager()->canvas($width, $height);
        $mask->circle($width, $width / 2, $height / 2, function ($draw) {
            $draw->background('#fff');
        });

        $image
            ->mask($mask, false)
            ->save($path . '.circle.png');

        return $path . '.circle.png';
    }

    /**
     * @param string $url
     * @return string
     */
    public function makeBackground(string $url): string
    {
        $path = storage_path('/tmp/' . Str::random() . '.png');
        file_put_contents($path, file_get_contents($url));

        $this->getImageManager()->make($path)
            ->encode('png')
            ->fit($this->getWidth(), $this->getHeight())
            ->save($path . '.fit.png');

        return $path . '.fit.png';
    }

    /**
     */
    public function __construct()
    {
        $this
            ->setImageManager(new ImageManager)
            ->setWidth(470)
            ->setHeight(140);
    }

    /**
     * @return Image
     */
    public function make(): Image
    {
        $this->setCanvas($this->getImageManager()->canvas(
            $this->getWidth(),
            $this->getHeight(),
        ))->getCanvas()->fill($this->attributes['background_color']);


        $avatar_path = $this->attributes['avatar_image_url'] == null ? null : $this->makeAvatar($this->attributes['avatar_image_url']);
        $background_path = $this->attributes['background_image_url'] == null ? null : $this->makeBackground($this->attributes['background_image_url']);
        $percentage = $this->attributes['percentage'];
        $this->attributes['foreground_color'] = $this->attributes['foreground_color'] ?? static::getDominantColor($background_path);

        if ($background_path != null) {
            $this->setCanvas($this->getCanvas()->insert($background_path, 'center'));
        }

        $this->setCanvas($this->getCanvas()->rectangle(10, 10, $this->getWidth() - 10, $this->getHeight() - 10, function (RectangleShape $draw) {
            $color = Hex::fromString($this->attributes['card_background_color'])->toRgba($this->attributes['card_opacity']);
            $color = $color->__toString();
            $draw->background($color);
        }));

        if ($avatar_path != null) {
            $this->setCanvas(
                $this->getCanvas()
                    ->insert($avatar_path, 'top-left', 20, ((140 / 2) - (80 / 2)))
            );
        }

        return $this->getCanvas()
            ->text($this->attributes['username'], 80 + 30, 45, function ($font) {
                /** @var Font $font */
                $font->file(storage_path('/fonts/Montserrat-Bold.ttf'));
                $font->size(16);
                $font->color($this->attributes['text_color']);
                $font->align('left');
                $font->valign('middle');
            })
            ->text($this->attributes['rank'], 80 + 30, 45 + 16 + 6, function ($font) {
                /** @var Font $font */
                $font->file(storage_path('/fonts/Montserrat-Bold.ttf'));
                $font->size(12);
                $font->color($this->attributes['text_color']);
                $font->align('left');
                $font->valign('middle');
            })
            ->text($this->attributes['level'], 80 + 30, 45 + 16 + 5 + 16, function ($font) {
                /** @var Font $font */
                $font->file(storage_path('/fonts/Montserrat-Bold.ttf'));
                $font->size(14);
                $font->color($this->attributes['foreground_color']);
                $font->align('left');
                $font->valign('middle');
            })
            ->rectangle(110, 95, 340 + 110, 105, function (RectangleShape $draw) {
                $draw->background($this->attributes['background_color']);
            })
            ->rectangle(110, 95, ((340 * $percentage) / 100) + 110, 105, function (RectangleShape $draw) {
                $draw->background($this->attributes['foreground_color']);
            })
            ->text($this->attributes['xp'], $this->getWidth() - 20, 45 + 16 + 5 + 16, function ($font) {
                /** @var Font $font */
                $font->file(storage_path('/fonts/Montserrat-Bold.ttf'));
                $font->size(14);
                $font->color($this->attributes['foreground_color']);
                $font->align('right');
                $font->valign('middle');
            })
            ->encode('png');
    }
}
