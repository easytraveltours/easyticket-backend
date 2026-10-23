<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'discount_percent',
        'credit_limit',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'decimal:2',
            'credit_limit' => 'decimal:2',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'group_id');
    }
}
