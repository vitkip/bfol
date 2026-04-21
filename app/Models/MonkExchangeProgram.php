<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class MonkExchangeProgram extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title_lo', 'title_en', 'title_zh',
        'destination_country', 'partner_org_id', 'year',
        'application_open', 'application_deadline', 'program_start', 'program_end',
        'monks_quota', 'monks_selected',
        'description_lo', 'description_en', 'description_zh',
        'requirements_lo', 'requirements_en', 'requirements_zh',
        'application_url', 'contact_email', 'status', 'is_featured', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'application_open'     => 'date',
            'application_deadline' => 'date',
            'program_start'        => 'date',
            'program_end'          => 'date',
            'is_featured'          => 'boolean',
        ];
    }

    public function partnerOrganization()
    {
        return $this->belongsTo(PartnerOrganization::class, 'partner_org_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function applications()
    {
        return $this->hasMany(MonkExchangeApplication::class, 'program_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
