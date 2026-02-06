<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'class_id',
        'gender',
        'birth_date',
        'birth_place',
        'address',
        'parent_name',
        'parent_phone',
        'parent_email',
        'enrollment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function arrears(): HasMany
    {
        return $this->hasMany(Arrears::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeWithArrears($query)
    {
        return $query->whereHas('arrears', function ($q) {
            $q->where('arrears_amount', '>', 0);
        });
    }

    public function getTotalArrearsAttribute()
    {
        return $this->arrears()->sum('arrears_amount');
    }

    public function getPaymentStatusAttribute()
    {
        $unpaidBills = $this->bills()->where('status', 'unpaid')->count();

        if ($unpaidBills === 0) {
            return 'paid';
        }

        $partialBills = $this->bills()->where('status', 'partial')->count();

        if ($partialBills > 0) {
            return 'partial';
        }

        return 'unpaid';
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
