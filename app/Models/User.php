<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'whatsapp',
        'company_name',
        'travel_agency_name',
        'registration_number',
        'business_address',
        'country',
        'city',
        'website',
        'expected_monthly_bookings',
        'years_in_business',
        'documents_url',
        'group_id',
        'wallet_balance',
        'credit_limit',
        'last_login',
        'last_activity',
        'current_page',
        'is_online',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'last_activity' => 'datetime',
            'wallet_balance' => 'decimal:2',
            'credit_limit' => 'decimal:2',
            'is_online' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(AgentGroup::class, 'group_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'agent_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'agent_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
