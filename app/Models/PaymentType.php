<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'default_amount',
        'frequency',
        'is_mandatory',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }
}
