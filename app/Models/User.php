<?php

namespace App\Models;

use Core\Model\Model;

class User extends Model
{
    protected string $table = 'users';
    
    protected array $fillable = [
        'name',
        'email',
        'password'
    ];

    protected array $hidden = [
        'password',
        'remember_token'
    ];

    protected array $casts = [
        'email_verified_at' => 'datetime',
        'settings' => 'json'
    ];

    // Mutator
    protected function setPasswordAttribute($value): string
    {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    // Accessor
    protected function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
} 