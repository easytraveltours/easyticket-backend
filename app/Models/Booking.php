<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'ticket_id',
        'agent_id',
        'seats_count',
        'passenger_title',
        'passenger_first_name',
        'passenger_last_name',
        'passenger_passport',
        'passenger_nationality',
        'unit_price',
        'total_amount',
        'currency',
        'status',
        'expires_at',
        'payment_method',
        'payment_tx_id',
        'payment_amount',
        'payment_proof_url',
        'payment_proof_notes',
        'payment_submitted_at',
        'verified_at',
        'verified_by_admin_id',
        'admin_notes',
        'activated_at',
        'pnr_issued',
        'e_ticket_number',
    ];

    protected function casts(): array
    {
        return [
            'seats_count' => 'integer',
            'unit_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'payment_amount' => 'decimal:2',
            'expires_at' => 'datetime',
            'payment_submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'activated_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'booking_id');
    }

    public function isReserved(): bool
    {
        return $this->status === 'RESERVED';
    }

    public function isPaymentVerification(): bool
    {
        return $this->status === 'PAYMENT_VERIFICATION';
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function isExpired(): bool
    {
        return $this->status === 'EXPIRED' || ($this->status === 'RESERVED' && $this->expires_at && $this->expires_at->isPast());
    }
}
