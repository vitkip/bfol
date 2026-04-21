<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class MouAgreement extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh', 'partner_org_id',
        'signed_date', 'expiry_date', 'document_url', 'status',
        'description_lo', 'description_en', 'description_zh',
        'signers_lo', 'signers_en', 'signers_zh',
        'scope_lo', 'scope_en', 'scope_zh',
    ];

    protected function casts(): array
    {
        return ['signed_date' => 'date', 'expiry_date' => 'date'];
    }

    public function partnerOrganization()
    {
        return $this->belongsTo(PartnerOrganization::class, 'partner_org_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
