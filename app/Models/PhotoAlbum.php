<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh',
        'description_lo', 'description_en', 'description_zh',
        'cover_image', 'event_id', 'is_public',
    ];

    protected function casts(): array
    {
        return ['is_public' => 'boolean'];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function images()
    {
        return $this->hasMany(PhotoAlbumImage::class, 'album_id')->orderBy('sort_order');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
