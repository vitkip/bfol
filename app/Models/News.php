<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class News extends Model
{
    use HasSlug, HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh', 'slug',
        'excerpt_lo', 'excerpt_en', 'excerpt_zh',
        'content_lo', 'content_en', 'content_zh',
        'thumbnail', 'category_id', 'author_id',
        'status', 'is_featured', 'is_urgent', 'view_count', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured'  => 'boolean',
            'is_urgent'    => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title_lo')->saveSlugsTo('slug')->doNotGenerateSlugsOnUpdate();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tags');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
