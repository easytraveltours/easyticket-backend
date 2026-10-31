<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_notes',
        'ip_address',
    ];

    /**
     * Scope for new/unread inquiries.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}

