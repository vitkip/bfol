<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasTranslations;

    protected $fillable = [
        'tag_lo', 'tag_en', 'tag_zh',
        'title_lo', 'title_en', 'title_zh',
        'subtitle_lo', 'subtitle_en', 'subtitle_zh',
        'image_url',
        'btn1_text_lo', 'btn1_text_en', 'btn1_text_zh', 'btn1_url',
        'btn2_text_lo', 'btn2_text_en', 'btn2_text_zh', 'btn2_url',
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
