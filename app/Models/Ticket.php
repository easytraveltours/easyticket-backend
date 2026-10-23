<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'pnr',
        'airline_name',
        'flight_number',
        'dep_airport_code',
        'dep_airport_name',
        'dep_city',
        'dep_country',
        'dep_date',
        'dep_time',
        'arr_airport_code',
        'arr_airport_name',
        'arr_city',
        'arr_country',
        'arr_date',
        'arr_time',
        'duration',
        'stops',
        'cabin_class',
        'baggage',
        'is_refundable',
        'ticket_rules',
        'refund_rules',
        'change_rules',
        'public_price',
        'b2b_price',
        'currency',
        'total_inventory',
        'available_inventory',
        'reserved_inventory',
        'active_inventory',
        'status',
        'low_stock_threshold',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'is_refundable' => 'boolean',
            'public_price' => 'decimal:2',
            'b2b_price' => 'decimal:2',
            'stops' => 'integer',
            'total_inventory' => 'integer',
            'available_inventory' => 'integer',
            'reserved_inventory' => 'integer',
            'active_inventory' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'ticket_id');
    }

    public function isLowStock(): bool
    {
        return $this->available_inventory <= $this->low_stock_threshold;
    }
}
