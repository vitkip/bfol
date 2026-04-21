<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class PartnerOrganization extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name_lo', 'name_en', 'name_zh', 'acronym',
        'country_code', 'country_name_lo', 'country_name_en', 'country_name_zh',
        'logo_url', 'website_url',
        'description_lo', 'description_en', 'description_zh',
        'contact_person', 'contact_email', 'contact_phone',
        'type', 'partnership_since', 'status', 'sort_order',
    ];

    public function mouAgreements()
    {
        return $this->hasMany(MouAgreement::class, 'partner_org_id');
    }

    public function monkPrograms()
    {
        return $this->hasMany(MonkExchangeProgram::class, 'partner_org_id');
    }

    public function aidProjects()
    {
        return $this->hasMany(AidProject::class, 'partner_org_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
