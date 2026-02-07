<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BosBudget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year',
        'amount',
        'used',
        'remaining',
        'description',
    ];

    protected $casts = [
        'year' => 'integer',
        'amount' => 'decimal:2',
        'used' => 'decimal:2',
        'remaining' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(BosTransaction::class);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->amount == 0)
            return 0;
        return ($this->used / $this->amount) * 100;
    }
}
