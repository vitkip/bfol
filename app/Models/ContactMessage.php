<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message',
        'language', 'is_read', 'replied_by', 'replied_at', 'ip_address',
    ];

    protected function casts(): array
    {
        return ['is_read' => 'boolean', 'replied_at' => 'datetime', 'created_at' => 'datetime'];
    }

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
