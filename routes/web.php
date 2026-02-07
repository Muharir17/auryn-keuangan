<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
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

    Route::resource('users', UserController::class);
});
