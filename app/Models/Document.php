<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh',
        'file_url', 'file_type', 'file_size_kb', 'category_id',
        'description_lo', 'description_en', 'description_zh',
        'is_public', 'download_count', 'published_at', 'author_id',
    ];

    protected function casts(): array
    {
        return ['is_public' => 'boolean', 'published_at' => 'datetime'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
