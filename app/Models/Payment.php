<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number',
        'bill_id',
        'student_id',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
        'status',
        'uploaded_by',
        'validator_id',
        'validated_at',
        'rejection_reason',
        'receipt_number',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'validated_at' => 'datetime',
        'metadata' => 'json',
    ];

    protected $dates = [
        'payment_date',
        'validated_at',
        'deleted_at',
    ];

    // Relationships
    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function paymentSlips()
    {
        return $this->hasMany(PaymentSlip::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="badge badge-warning">Menunggu</span>',
            'validated' => '<span class="badge badge-success">Tervalidasi</span>',
            'rejected' => '<span class="badge badge-danger">Ditolak</span>',
            default => '<span class="badge badge-secondary">' . $this->status . '</span>',
        };
    }

    // Methods
    public function approve(User $validator, string $receiptNumber = null)
    {
        $this->update([
            'status' => 'approved',
            'validator_id' => $validator->id,
            'validated_at' => now(),
            'receipt_number' => $receiptNumber,
        ]);
    }

    public function validate(User $validator, string $receiptNumber = null)
    {
        return $this->approve($validator, $receiptNumber);
    }

    public function reject(User $validator, string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'validator_id' => $validator->id,
            'rejection_reason' => $reason,
        ]);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isValidated()
    {
        return $this->status === 'validated';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
