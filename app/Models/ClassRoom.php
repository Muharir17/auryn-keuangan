<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'level',
        'academic_year',
        'homeroom_teacher_id',
        'student_count',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'student_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function bills(): HasManyThrough
    {
        return $this->hasManyThrough(Bill::class, Student::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByAcademicYear($query, string $year)
    {
        return $query->where('academic_year', $year);
    }

    public function getTotalArrearsAttribute()
    {
        return $this->students->sum(function ($student) {
            return $student->total_arrears ?? 0;
        });
    }

    public function getPaymentCompletionPercentageAttribute()
    {
        $totalStudents = $this->students()->count();
        if ($totalStudents === 0) {
            return 0;
        }

        $paidStudents = $this->students()->whereHas('bills', function ($query) {
            $query->where('status', 'paid');
        })->count();

        return round(($paidStudents / $totalStudents) * 100, 2);
    }
}
