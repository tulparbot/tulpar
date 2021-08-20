<?php

namespace App\Tulpar\Image;

use App\Tulpar\Contracts\ImageGeneratorInterface;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

abstract class BaseGenerator implements ImageGeneratorInterface
{
    protected ImageManager $imageManager;

    protected Image $canvas;

    protected int $width;

    protected int $height;

    public function make(): Image
    {
        return $this->getCanvas();
    }

    public function getImageManager(): ImageManager
    {
        return $this->imageManager;
    }

    public function setImageManager(ImageManager $imageManager): static
    {
        $this->imageManager = $imageManager;
        return $this;
    }

    public function getCanvas(): Image
    {
        return $this->canvas;
    }

    public function setCanvas(Image $image): static
    {
        $this->canvas = $image;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;
        return $this;
    }
}
