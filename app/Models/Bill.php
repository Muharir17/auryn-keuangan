<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_number',
        'student_id',
        'payment_type_id',
        'month',
        'year',
        'amount',
        'discount',
        'final_amount',
        'due_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function arrears(): HasOne
    {
        return $this->hasOne(Arrears::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereIn('status', ['unpaid', 'partial']);
    }

    public function scopeByMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()
                    ->where('status', 'validated')
                    ->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->final_amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && in_array($this->status, ['unpaid', 'partial']);
    }

    public function calculateFinalAmount()
    {
        $this->final_amount = $this->amount - $this->discount;
        $this->save();
    }

    public function updateStatus()
    {
        $paidAmount = $this->paid_amount;

        if ($paidAmount >= $this->final_amount) {
            $this->status = 'paid';
        } elseif ($paidAmount > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'unpaid';
        }

        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            if (empty($bill->bill_number)) {
                $bill->bill_number = 'BILL-' . date('Y-m') . '-' . str_pad(static::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }

            if (empty($bill->final_amount)) {
                $bill->final_amount = $bill->amount - $bill->discount;
            }
        });
    }
}
