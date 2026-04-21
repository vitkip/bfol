<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class TranslationProject extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh',
        'source_language', 'target_language',
        'description_lo', 'description_en', 'description_zh',
        'document_url', 'translator', 'year', 'status',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
