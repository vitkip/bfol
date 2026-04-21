<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonkExchangeApplication extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'program_id', 'monk_name_lo', 'monk_name_en', 'temple_name_lo',
        'province', 'phone', 'years_ordained', 'languages', 'documents_url',
        'status', 'notes', 'reviewed_by', 'reviewed_at', 'submitted_at',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime', 'submitted_at' => 'datetime', 'updated_at' => 'datetime'];
    }

    public function program()
    {
        return $this->belongsTo(MonkExchangeProgram::class, 'program_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
