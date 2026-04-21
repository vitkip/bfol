<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasTranslations;

    protected $fillable = [
        'username', 'email', 'password', 'full_name_lo', 'full_name_en', 'full_name_zh',
        'role', 'avatar_url', 'is_active', 'last_login',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login'        => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'author_id');
    }
}
