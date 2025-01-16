<?php

namespace Core\Model;

use Core\Database\QueryBuilder;
use Core\Upload\UploadedFile;
use Core\Upload\FileUploader;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = ['password'];
    protected array $casts = [];
    protected array $with = [];  // For eager loading relationships
    protected bool $timestamps = true;
    protected static array $booted = [];
    protected array $attributes = [];
    protected array $original = [];
    protected array $changes = [];
    protected array $files = [];

    public function __construct(array $attributes = [])
    {
        $this->bootIfNotBooted();
        $this->fill($attributes);
    }

    protected function bootIfNotBooted(): void
    {
        $class = get_class($this);
        if (!isset(static::$booted[$class])) {
            static::boot();
            static::$booted[$class] = true;
        }
    }

    protected static function boot(): void
    {
        // Hook for model events
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    public function setAttribute(string $key, $value): void
    {
        if (method_exists($this, 'set' . ucfirst($key) . 'Attribute')) {
            $value = $this->{'set' . ucfirst($key) . 'Attribute'}($value);
        }
        $this->attributes[$key] = $value;
    }

    public function getAttribute(string $key)
    {
        if (method_exists($this, 'get' . ucfirst($key) . 'Attribute')) {
            return $this->{'get' . ucfirst($key) . 'Attribute'}($this->attributes[$key] ?? null);
        }
        return $this->attributes[$key] ?? null;
    }

    public static function query(): QueryBuilder
    {
        return app()->db->table((new static)->table);
    }

    public static function create(array $attributes)
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }

    public function save(): bool
    {
        if ($this->timestamps) {
            $this->updateTimestamps();
        }

        $attributes = $this->getAttributes();
        
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->update($attributes);
        }

        return $this->insert($attributes);
    }

    protected function insert(array $attributes): bool
    {
        $id = static::query()->insert($attributes);
        $this->setAttribute($this->primaryKey, $id);
        return true;
    }

    protected function update(array $attributes): bool
    {
        return static::query()
            ->where($this->primaryKey, '=', $this->getAttribute($this->primaryKey))
            ->update($attributes);
    }

    public static function find($id)
    {
        return static::query()
            ->where('id', '=', $id)
            ->first();
    }

    // Relationships
    protected function hasOne(string $related, string $foreignKey = null, string $localKey = null)
    {
        return new HasOne($this, new $related, $foreignKey, $localKey ?? $this->primaryKey);
    }

    protected function hasMany(string $related, string $foreignKey = null, string $localKey = null)
    {
        return new HasMany($this, new $related, $foreignKey, $localKey ?? $this->primaryKey);
    }

    protected function belongsTo(string $related, string $foreignKey = null, string $ownerKey = null)
    {
        return new BelongsTo($this, new $related, $foreignKey, $ownerKey ?? 'id');
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function isDirty(string $attribute = null): bool
    {
        return !empty($this->getDirty($attribute));
    }

    public function getDirty(string $attribute = null): array
    {
        if ($attribute) {
            return array_key_exists($attribute, $this->changes) 
                ? [$attribute => $this->changes[$attribute]] 
                : [];
        }
        return $this->changes;
    }

    protected function updateTimestamps(): void
    {
        $time = date('Y-m-d H:i:s');

        if (!$this->exists()) {
            $this->setAttribute('created_at', $time);
        }

        $this->setAttribute('updated_at', $time);
    }

    public function exists(): bool
    {
        return isset($this->attributes[$this->primaryKey]);
    }

    public function fresh(): ?self
    {
        if (!$this->exists()) {
            return null;
        }

        return static::find($this->getAttribute($this->primaryKey));
    }

    public function refresh(): self
    {
        if ($fresh = $this->fresh()) {
            $this->attributes = $fresh->attributes;
            $this->original = $fresh->original;
            $this->changes = [];
        }

        return $this;
    }

    protected function castAttribute($value, string $type)
    {
        switch ($type) {
            case 'int':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'bool':
                return (bool) $value;
            case 'array':
            case 'json':
                return is_string($value) ? json_decode($value, true) : $value;
            case 'datetime':
                return new \DateTime($value);
            default:
                return $value;
        }
    }

    public function toArray(): array
    {
        $attributes = [];

        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $this->hidden)) {
                continue;
            }

            if (isset($this->casts[$key])) {
                $value = $this->castAttribute($value, $this->casts[$key]);
            }

            $attributes[$key] = $value;
        }

        return $attributes;
    }

    public function addFile(string $attribute, UploadedFile $file): void
    {
        $this->files[$attribute] = $file;
        $this->setAttribute($attribute, $file->getPath());
    }

    protected function uploadFile(string $attribute, $file): ?UploadedFile
    {
        $uploader = new FileUploader();
        return $uploader->upload($file, $this->getUploadDirectory());
    }

    protected function getUploadDirectory(): string
    {
        return strtolower(class_basename($this)) . 's';
    }

    public function deleteFile(string $attribute): bool
    {
        if (isset($this->files[$attribute])) {
            return $this->files[$attribute]->delete();
        }
        return false;
    }
} 