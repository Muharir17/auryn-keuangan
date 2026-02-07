<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine if the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isFinance() || $user->isTeacher();
    }

    /**
     * Determine if the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        // Admin and Finance can view all payments
        if ($user->isAdmin() || $user->isFinance()) {
            return true;
        }

        // Teachers can view payments of their own students
        if ($user->isTeacher()) {
            return $user->classes()->whereHas('students', function ($query) use ($payment) {
                $query->where('id', $payment->student_id);
            })->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create payments.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isFinance() || $user->isTeacher();
    }

    /**
     * Determine if the user can update the payment.
     */
    public function update(User $user, Payment $payment): bool
    {
        // Only allow editing pending payments
        if ($payment->status !== 'pending') {
            return false;
        }

        // Admin and Finance can edit all pending payments
        if ($user->isAdmin() || $user->isFinance()) {
            return true;
        }

        // Teachers can edit payments of their own students
        if ($user->isTeacher()) {
            return $user->classes()->whereHas('students', function ($query) use ($payment) {
                $query->where('id', $payment->student_id);
            })->exists();
        }

        return false;
    }

    /**
     * Determine if the user can delete the payment.
     */
    public function delete(User $user, Payment $payment): bool
    {
        // Only allow deleting pending payments
        if ($payment->status !== 'pending') {
            return false;
        }

        // Admin and Finance can delete all pending payments
        if ($user->isAdmin() || $user->isFinance()) {
            return true;
        }

        // Teachers can delete payments of their own students
        if ($user->isTeacher()) {
            return $user->classes()->whereHas('students', function ($query) use ($payment) {
                $query->where('id', $payment->student_id);
            })->exists();
        }

        return false;
    }

    /**
     * Determine if the user can validate payments.
     */
    public function validate(User $user): bool
    {
        return $user->isAdmin() || $user->isFinance();
    }

    /**
     * Determine if the user can bulk upload payments.
     */
    public function bulkUpload(User $user): bool
    {
        return $user->isAdmin() || $user->isFinance() || $user->isTeacher();
    }

    /**
     * Determine if the user can view payment validation queue.
     */
    public function viewValidationQueue(User $user): bool
    {
        return $user->isAdmin() || $user->isFinance();
    }
}
