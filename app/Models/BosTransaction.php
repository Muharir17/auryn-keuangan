<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BosTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bos_budget_id',
        'date',
        'description',
        'type',
        'amount',
        'category',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function budget()
    {
        return $this->belongsTo(BosBudget::class, 'bos_budget_id');
    }
}
