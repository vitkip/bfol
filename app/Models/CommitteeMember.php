<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name_lo', 'name_en', 'name_zh',
        'title_lo', 'title_en', 'title_zh',
        'position_lo', 'position_en', 'position_zh',
        'department', 'photo_url',
        'bio_lo', 'bio_en', 'bio_zh',
        'email', 'phone', 'term_start', 'term_end',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
