<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class PhotoAlbumImage extends Model
{
    use HasTranslations;

    public $timestamps = false;
    protected $fillable = ['album_id', 'image_url', 'caption_lo', 'caption_en', 'caption_zh', 'sort_order'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function album()
    {
        return $this->belongsTo(PhotoAlbum::class, 'album_id');
    }
}
