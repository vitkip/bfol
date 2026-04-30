<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTranslations;

    // Role hierarchy: higher number = more permissions
    public const ROLES = [
        'viewer'     => 1,
        'editor'     => 2,
        'admin'      => 3,
        'superadmin' => 4,
    ];

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

    // ─── Role helpers ────────────────────────────────────────────────────────────

    /** True if user's role level >= the required minimum */
    public function hasMinRole(string $minRole): bool
    {
        return (self::ROLES[$this->role] ?? 0) >= (self::ROLES[$minRole] ?? 999);
    }

    public function isSuperAdmin(): bool { return $this->role === 'superadmin'; }
    public function isAdmin(): bool      { return $this->hasMinRole('admin'); }
    public function isEditor(): bool     { return $this->hasMinRole('editor'); }
    public function isViewer(): bool     { return $this->hasMinRole('viewer'); }

    // ─── Relationships ────────────────────────────────────────────────────────────

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'author_id');
    }
}
