<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'title_lo', 'title_en', 'title_zh',
        'content_lo', 'content_en', 'content_zh',
        'meta_title_lo', 'meta_title_en', 'meta_title_zh', 'meta_description',
        'thumbnail', 'parent_slug', 'sort_order', 'is_published', 'author_id',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
