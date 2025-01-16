<?php

namespace Core;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];
    protected array $casts = [];

    public function __construct()
    {
        $this->db = Application::getInstance()->container->get(Database::class);
    }

    public function find(int $id)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, '=', $id)
            ->first();
    }

    public function all()
    {
        return $this->db->table($this->table)->get();
    }

    public function create(array $data)
    {
        $fillableData = $this->filterFillableData($data);
        return $this->db->table($this->table)->insert($fillableData);
    }

    public function update(int $id, array $data)
    {
        $fillableData = $this->filterFillableData($data);
        return $this->db->table($this->table)
            ->where($this->primaryKey, '=', $id)
            ->update($fillableData);
    }

    public function delete(int $id)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, '=', $id)
            ->delete();
    }

    protected function filterFillableData(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }

    public function toArray(): array
    {
        $attributes = get_object_vars($this);
        
        // Remove hidden attributes
        foreach ($this->hidden as $hidden) {
            unset($attributes[$hidden]);
        }

        // Apply casts
        foreach ($this->casts as $key => $type) {
            if (isset($attributes[$key])) {
                $attributes[$key] = $this->castAttribute($attributes[$key], $type);
            }
        }

        return $attributes;
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
                return json_decode($value, true);
            default:
                return $value;
        }
    }
} 