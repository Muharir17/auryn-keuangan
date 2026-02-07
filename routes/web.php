<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('classes', ClassController::class);
    Route::get('classes/{class}/students', [ClassController::class, 'students'])->name('classes.students');
    Route::get('classes/{class}/payment-status', [ClassController::class, 'paymentStatus'])->name('classes.payment-status');

    Route::resource('students', StudentController::class);
    Route::get('students/{student}/payment-history', [StudentController::class, 'paymentHistory'])->name('students.payment-history');
    Route::get('students/{student}/arrears-detail', [StudentController::class, 'arrearsDetail'])->name('students.arrears-detail');

    Route::resource('payment-types', PaymentTypeController::class);

    Route::resource('bills', BillController::class);
    Route::post('bills/generate-monthly', [BillController::class, 'generateMonthly'])->name('bills.generate-monthly');

    // Wali Kelas Management (Teacher users only)
    Route::resource('users', UserController::class);
    Route::redirect('/users/wali-kelas', '/users', 301);

    // Admin: User Management (All users with roles)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::post('users/{user}/assign-ability', [AdminUserController::class, 'assignAbility'])->name('users.assign-ability');
        Route::post('users/{user}/remove-ability', [AdminUserController::class, 'removeAbility'])->name('users.remove-ability');
    });

    // Roles & Abilities Management
    Route::resource('roles', RoleController::class);
    Route::get('roles/abilities/list', [RoleController::class, 'getAbilities'])->name('roles.abilities');

    Route::resource('abilities', AbilityController::class);

    // Redirect /permissions to /abilities (Bouncer uses "abilities" terminology)
    Route::redirect('/permissions', '/abilities', 301);

    // Payment Routes
    Route::resource('payments', PaymentController::class);
    Route::get('payments/validation-queue', [PaymentController::class, 'validationQueue'])->name('payments.validation-queue');
    Route::post('payments/{payment}/validate', [PaymentController::class, 'validatePayment'])->name('payments.validate');
    Route::get('payments/bulk-upload', [PaymentController::class, 'bulkUpload'])->name('payments.bulk-upload');
    Route::post('payments/bulk-upload', [PaymentController::class, 'processBulkUpload'])->name('payments.bulk-upload.process');

    // Profile Routes
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Report Routes
    Route::get('reports/financial', [App\Http\Controllers\ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/payments', [App\Http\Controllers\ReportController::class, 'payments'])->name('reports.payments');

    // PDF Export Routes
    Route::get('reports/financial/pdf', [App\Http\Controllers\ReportController::class, 'financialPdf'])->name('reports.financial.pdf');
    Route::get('reports/payments/pdf', [App\Http\Controllers\ReportController::class, 'paymentsPdf'])->name('reports.payments.pdf');
    Route::get('reports/bills/pdf', [App\Http\Controllers\ReportController::class, 'billsPdf'])->name('reports.bills.pdf');

    // Audit Log Routes
    Route::get('logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('logs.index');

    // BOS Routes
    Route::get('bos/budgets', [App\Http\Controllers\BosController::class, 'budgets'])->name('bos.budgets');
    Route::get('bos/transactions', [App\Http\Controllers\BosController::class, 'transactions'])->name('bos.transactions');
    Route::get('bos/budgets/pdf', [App\Http\Controllers\BosController::class, 'budgetsPdf'])->name('bos.budgets.pdf');
    Route::get('bos/transactions/pdf', [App\Http\Controllers\BosController::class, 'transactionsPdf'])->name('bos.transactions.pdf');

    // Proposal Routes
    Route::get('proposals', [App\Http\Controllers\ProposalController::class, 'index'])->name('proposals.index');
    Route::get('proposals/approval', [App\Http\Controllers\ProposalController::class, 'approval'])->name('proposals.approval');

    // Settings Routes
    Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});
