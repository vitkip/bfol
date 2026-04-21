<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh',
        'subtitle_lo', 'subtitle_en', 'subtitle_zh',
        'image_url',
        'btn_text_lo', 'btn_text_en', 'btn_text_zh', 'btn_url',
        'style', 'position', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAtPosition($query, string $position)
    {
        return $query->where('position', $position)->orderBy('sort_order');
    }
}
