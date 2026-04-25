<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasTranslations;

    protected $fillable = [
        'department_id',
        'gender',
        'first_name_lo', 'first_name_en', 'first_name_zh',
        'last_name_lo',  'last_name_en',  'last_name_zh',
        'name_lo', 'name_en', 'name_zh',
        'title_lo', 'title_en', 'title_zh',
        'position_lo', 'position_en', 'position_zh',
        'photo_url',
        'bio_lo', 'bio_en', 'bio_zh',
        'email', 'phone', 'facebook',
        'date_of_birth', 'date_of_ordination', 'pansa',
        'education_lo', 'education_en', 'education_zh',
        'birth_village_lo', 'birth_village_en', 'birth_village_zh',
        'district_lo', 'district_en', 'district_zh',
        'province_lo', 'province_en', 'province_zh',
        'current_temple_lo', 'current_temple_en', 'current_temple_zh',
        'term_start', 'term_end',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'          => 'boolean',
            'date_of_birth'      => 'date',
            'date_of_ordination' => 'date',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }
}
