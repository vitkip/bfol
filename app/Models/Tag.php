<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tag extends Model
{
    use HasSlug, HasTranslations;

    public $timestamps = false;
    protected $fillable = ['name_lo', 'name_en', 'name_zh', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn ($m) => $m->name_en ?: 'tag')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_tags');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tags');
    }
}
