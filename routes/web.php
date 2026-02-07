<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ArrearController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BosController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard (All Authenticated Users)
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Profile Routes (All Authenticated Users)
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Student Management
    // 'view-students' ability required for index/show
    // 'manage-students' ability required for create/edit/delete (handled in controller or policy if needed strictly)
    Route::resource('students', StudentController::class)->middleware('can:view-students');
    Route::get('students/{student}/payment-history', [StudentController::class, 'paymentHistory'])->name('students.payment-history')->middleware('can:view-students');
    Route::get('students/{student}/arrears-detail', [StudentController::class, 'arrearsDetail'])->name('students.arrears-detail')->middleware('can:view-students');

    // Payment Management
    // 'view-payments' ability required
    Route::resource('payments', PaymentController::class)->middleware('can:view-payments');
    Route::get('payments/{payment}/print', [PaymentController::class, 'printReceipt'])->name('payments.print')->middleware('can:view-payments');
    Route::get('payments/validation-queue', [PaymentController::class, 'validationQueue'])->name('payments.validation-queue')->middleware('can:validate-payments');
    Route::post('payments/{payment}/validate', [PaymentController::class, 'validatePayment'])->name('payments.validate')->middleware('can:validate-payments');
    Route::get('payments/bulk-upload', [PaymentController::class, 'bulkUpload'])->name('payments.bulk-upload')->middleware('can:bulk-upload-payments');
    Route::post('payments/bulk-upload', [PaymentController::class, 'processBulkUpload'])->name('payments.bulk-upload.process')->middleware('can:bulk-upload-payments');

    // Arrears Management
    Route::get('arrears', [ArrearController::class, 'index'])->name('arrears.index')->middleware('can:view-arrears');

    // ---------------------------------------------------------------------
    // Restricted Routes (Admin / Finance / Principal / Foundation Only)
    // ---------------------------------------------------------------------

    // Class Management
    Route::middleware(['can:manage-classes'])->group(function () {
        Route::resource('classes', ClassController::class);
        Route::get('classes/promotion', [App\Http\Controllers\ClassPromotionController::class, 'index'])->name('classes.promotion');
        Route::get('classes/{class}/students-list', [App\Http\Controllers\ClassPromotionController::class, 'students'])->name('classes.students.list');
        Route::post('classes/promotion', [App\Http\Controllers\ClassPromotionController::class, 'process'])->name('classes.promotion.process');
        Route::get('classes/{class}/students', [ClassController::class, 'students'])->name('classes.students');
        Route::get('classes/{class}/payment-status', [ClassController::class, 'paymentStatus'])->name('classes.payment-status');
    });

    // Payment Type Management
    Route::resource('payment-types', PaymentTypeController::class)->middleware('can:manage-payment-types');

    // Bill Management
    Route::resource('bills', BillController::class)->middleware('can:manage-bills');
    Route::post('bills/generate-monthly', [BillController::class, 'generateMonthly'])->name('bills.generate-monthly')->middleware('can:manage-bills');

    // User Management (Teacher Users)
    Route::resource('users', UserController::class)->middleware('can:manage-users');
    Route::redirect('/users/wali-kelas', '/users', 301);

    // Admin User Management (All Users)
    Route::prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function () {
        Route::resource('users', AdminUserController::class);
        Route::post('users/{user}/assign-ability', [AdminUserController::class, 'assignAbility'])->name('users.assign-ability');
        Route::post('users/{user}/remove-ability', [AdminUserController::class, 'removeAbility'])->name('users.remove-ability');
    });

    // Role & Ability Management
    Route::middleware(['can:manage-roles'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::get('roles/abilities/list', [RoleController::class, 'getAbilities'])->name('roles.abilities');
        Route::resource('abilities', AbilityController::class);
        Route::redirect('/permissions', '/abilities', 301);
    });

    // Reports
    Route::middleware(['can:view-reports'])->group(function () {
        Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
        // PDF Exports
        Route::get('reports/financial/pdf', [ReportController::class, 'financialPdf'])->name('reports.financial.pdf');
        Route::get('reports/payments/pdf', [ReportController::class, 'paymentsPdf'])->name('reports.payments.pdf');
        Route::get('reports/daily/pdf', [ReportController::class, 'dailyRecapPdf'])->name('reports.daily.pdf');
        Route::get('reports/bills/pdf', [ReportController::class, 'billsPdf'])->name('reports.bills.pdf');
    });

    // Audit Logs
    Route::get('logs', [AuditLogController::class, 'index'])->name('logs.index')->middleware('can:view-logs');

    // BOS Management
    Route::middleware(['can:view-bos'])->group(function () {
        Route::get('bos/budgets', [BosController::class, 'budgets'])->name('bos.budgets');
        Route::get('bos/transactions', [BosController::class, 'transactions'])->name('bos.transactions');
        Route::get('bos/budgets/pdf', [BosController::class, 'budgetsPdf'])->name('bos.budgets.pdf');
        Route::get('bos/transactions/pdf', [BosController::class, 'transactionsPdf'])->name('bos.transactions.pdf');
    });

    // Proposal Management
    Route::middleware(['can:view-proposals'])->group(function () {
        Route::get('proposals', [ProposalController::class, 'index'])->name('proposals.index');
    });

    Route::middleware(['can:approve-proposals'])->group(function () {
        Route::get('proposals/approval', [ProposalController::class, 'approval'])->name('proposals.approval');
    });

    // Settings
    Route::middleware(['can:manage-settings'])->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
