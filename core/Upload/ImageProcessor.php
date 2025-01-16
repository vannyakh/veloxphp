<?php

namespace Core\Upload;

class ImageProcessor
{
    protected $image;

    public function __construct(string $path)
    {
        $this->image = imagecreatefromstring(file_get_contents($path));
    }

    public function resize(int $width, int $height): self
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $newImage, 
            $this->image, 
            0, 0, 0, 0, 
            $width, $height, 
            imagesx($this->image), 
            imagesy($this->image)
        );
        $this->image = $newImage;
        return $this;
    }

    public function crop(int $width, int $height, int $x = 0, int $y = 0): self
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopy($newImage, $this->image, 0, 0, $x, $y, $width, $height);
        $this->image = $newImage;
        return $this;
    }

    public function save(string $path, int $quality = 90): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        return match ($extension) {
            'jpg', 'jpeg' => imagejpeg($this->image, $path, $quality),
            'png' => imagepng($this->image, $path, round($quality / 10)),
            'gif' => imagegif($this->image, $path),
            default => false,
        };
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }
} 