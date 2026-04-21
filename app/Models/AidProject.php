<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class AidProject extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh', 'country', 'partner_org_id', 'type',
        'description_lo', 'description_en', 'description_zh',
        'budget_usd', 'start_date', 'end_date', 'status', 'report_url', 'author_id',
    ];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'end_date' => 'date', 'budget_usd' => 'decimal:2'];
    }

    public function partnerOrganization()
    {
        return $this->belongsTo(PartnerOrganization::class, 'partner_org_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
