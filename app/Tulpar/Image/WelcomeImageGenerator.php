<?php

namespace App\Tulpar\Image;

use App\Support\Str;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

final class WelcomeImageGenerator extends BaseGenerator
{
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
            ->fit(120, 120);

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
     * @return string
     */
    public function getHash(): string
    {
        return md5($this->username . $this->welcome_text . $this->avatar_url . $this->background_url . $this->foreground_color);
    }

    /**
     * @return string
     */
    public function getCachedFilePath(): string
    {
        return storage_path('/tmp/' . $this->getHash() . '.png');
    }

    /**
     * @param string $username
     * @param string $welcome_text
     * @param string $avatar_url
     * @param string $background_url
     * @param string $foreground_color
     */
    public function __construct(
        public string      $username,
        public string      $welcome_text,
        public string      $avatar_url,
        public string|null $background_url = null,
        public string      $foreground_color = '#ffffff',
    )
    {
        $this
            ->setImageManager(new ImageManager)
            ->setWidth(500)
            ->setHeight(260);
    }

    /**
     * @return Image
     */
    public function make(): Image
    {
        if (file_exists($this->getCachedFilePath())) {
            return $this->getImageManager()->make(file_get_contents($this->getCachedFilePath()));
        }

        $background_path = null;
        if ($this->background_url !== null) {
            $background_path = $this->makeBackground($this->background_url);
        }

        $avatar_path = $this->makeAvatar($this->avatar_url);

        $this->setCanvas($this->getImageManager()->canvas(
            $this->getWidth(),
            $this->getHeight(),
        ))->getCanvas()->fill('#ffffff');

        if ($background_path !== null) {
            $this
                ->getCanvas()
                ->insert($background_path, 'center');
        }

        return $this->getCanvas()
            ->insert($avatar_path, 'top-left', 25, ((260 / 2) - (120 / 2)))
            ->text($this->welcome_text, 120 + 25 + 25, (260 / 2 - 16), function ($font) {
                /** @var Font $font */
                $font->file(storage_path('/fonts/Montserrat-SemiBold.ttf'));
                $font->size(14);
                $font->color($this->foreground_color);
                $font->align('left');
                $font->valign('middle');
            })
            ->text($this->username, 120 + 25 + 25, (260 / 2 + 10), function ($font) {
                /** @var Font $font */
                $font->file(storage_path('/fonts/Montserrat-Bold.ttf'));
                $font->size(18);
                $font->color($this->foreground_color);
                $font->align('left');
                $font->valign('middle');
            })
            ->encode('png')
            ->save($this->getCachedFilePath());
    }
}
