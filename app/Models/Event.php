<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasSlug, HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh', 'slug',
        'description_lo', 'description_en', 'description_zh',
        'thumbnail', 'location_lo', 'location_en', 'location_zh', 'country',
        'start_date', 'end_date', 'start_time', 'end_time',
        'category_id', 'organizer_lo', 'organizer_en', 'organizer_zh',
        'registration_url', 'registration_deadline', 'max_participants',
        'status', 'is_featured', 'is_international', 'author_id', 'view_count',
    ];

    protected function casts(): array
    {
        return [
            'start_date'            => 'date',
            'end_date'              => 'date',
            'registration_deadline' => 'date',
            'is_featured'           => 'boolean',
            'is_international'      => 'boolean',
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
        return $this->belongsToMany(Tag::class, 'event_tags');
    }

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class);
    }

    public function albums()
    {
        return $this->hasMany(PhotoAlbum::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')->where('start_date', '>=', now()->toDateString());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
