<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class MediaItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh', 'type',
        'file_url', 'thumbnail_url',
        'description_lo', 'description_en', 'description_zh',
        'category_id', 'event_id', 'platform', 'external_url',
        'duration_sec', 'file_size_kb', 'mime_type',
        'is_featured', 'view_count', 'download_count', 'published_at', 'author_id',
    ];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'published_at' => 'datetime'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
