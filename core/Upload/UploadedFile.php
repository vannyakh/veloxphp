<?php

namespace Core\Upload;

class UploadedFile
{
    protected string $name;
    protected string $originalName;
    protected string $mimeType;
    protected int $size;
    protected string $path;

    public function __construct(array $attributes)
    {
        $this->name = $attributes['name'];
        $this->originalName = $attributes['original_name'];
        $this->mimeType = $attributes['mime_type'];
        $this->size = $attributes['size'];
        $this->path = $attributes['path'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getExtension(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function delete(): bool
    {
        if (file_exists($this->path)) {
            return unlink($this->path);
        }
        return false;
    }
} 