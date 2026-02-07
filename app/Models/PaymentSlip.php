<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PaymentSlip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slip_number',
        'payment_id',
        'bill_id',
        'student_id',
        'uploaded_by',
        'original_filename',
        'file_path',
        'file_type',
        'file_size',
        'file_hash',
        'status',
        'notes',
        'rejection_reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'file_size' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    // Relationships
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Scopes
    public function scopeUploaded($query)
    {
        return $query->where('status', 'uploaded');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByUploader($query, $uploaderId)
    {
        return $query->where('uploaded_by', $uploaderId);
    }

    // Accessors
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'uploaded' => '<span class="badge badge-info">Diunggah</span>',
            'processing' => '<span class="badge badge-warning">Diproses</span>',
            'processed' => '<span class="badge badge-success">Diproses</span>',
            'rejected' => '<span class="badge badge-danger">Ditolak</span>',
            default => '<span class="badge badge-secondary">' . $this->status . '</span>',
        };
    }

    // Methods
    public function process()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsProcessed()
    {
        $this->update(['status' => 'processed']);
    }

    public function reject(string $reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function isUploaded()
    {
        return $this->status === 'uploaded';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isProcessed()
    {
        return $this->status === 'processed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentSlip) {
            if (empty($paymentSlip->slip_number)) {
                $paymentSlip->slip_number = 'SLIP-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::deleted(function ($paymentSlip) {
            $paymentSlip->deleteFile();
        });
    }
}
