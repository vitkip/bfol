<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class SiteStatistic extends Model
{
    use HasTranslations;

    public $timestamps = false;
    protected $fillable = ['label_lo', 'label_en', 'label_zh', 'value', 'icon', 'suffix', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'updated_at' => 'datetime'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
