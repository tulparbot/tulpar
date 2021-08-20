<?php

namespace App\Tulpar\Contracts;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;

interface ImageGeneratorInterface
{
    public function getImageManager(): ImageManager;

    public function setImageManager(ImageManager $imageManager): static;

    public function getCanvas(): Image;

    public function setCanvas(Image $image): static;

    public function getWidth(): int;

    public function setWidth(int $width): static;

    public function getHeight(): int;

    public function setHeight(int $height): static;

    public function make(): Image;
}
