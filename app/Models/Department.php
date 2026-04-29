<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'parent_id',
        'name_lo', 'name_en', 'name_zh',
        'description_lo', 'description_en', 'description_zh',
        'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function members()
    {
        return $this->hasMany(CommitteeMember::class);
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id')->orderBy('sort_order')->orderBy('name_lo');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name_lo');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Recursively collect all descendant IDs (to prevent circular parent). */
    public function getAllDescendantIds(): array
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            array_push($ids, ...$child->getAllDescendantIds());
        }
        return $ids;
    }

    /** True when this department has no parent. */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /** Full indented label for selects: "ຝ່າຍ A > Sub A1" */
    public function breadcrumbName(string $locale = 'lo'): string
    {
        $name = $this->{"name_{$locale}"} ?? $this->name_lo;
        if ($this->parent) {
            return $this->parent->breadcrumbName($locale) . ' › ' . $name;
        }
        return $name;
    }
}
