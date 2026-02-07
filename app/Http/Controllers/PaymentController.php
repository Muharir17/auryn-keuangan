<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentSlip;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'bill.paymentType', 'validator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);
        $students = Student::active()->get();

        return view('payments.index', compact('payments', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::active()->get();
        return view('payments.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'bill_id' => 'required|exists:bills,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,e-wallet,cheque,other',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'payment_slip' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if bill belongs to student
        $bill = Bill::findOrFail($request->bill_id);
        if ($bill->student_id != $request->student_id) {
            return back()->with('error', 'Tagihan tidak sesuai dengan siswa yang dipilih.')->withInput();
        }

        // Upload payment slip
        if ($request->hasFile('payment_slip')) {
            $file = $request->file('payment_slip');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('payment_slips', $fileName, 'public');

            // Create payment slip record
            $paymentSlip = PaymentSlip::create([
                'bill_id' => $bill->id,
                'student_id' => $request->student_id,
                'uploaded_by' => auth()->id(),
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_hash' => hash_file('sha256', $file->getPathname()),
                'notes' => $request->notes,
            ]);

            // Create payment record
            $payment = Payment::create([
                'bill_id' => $bill->id,
                'student_id' => $request->student_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Link payment slip to payment
            $paymentSlip->update(['payment_id' => $payment->id]);

            return redirect()->route('payments.index')
                ->with('success', 'Bukti pembayaran berhasil diunggah dan menunggu validasi.');
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran.')->withInput();
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['student', 'bill.paymentType', 'validator', 'paymentSlips']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        // Only allow editing pending payments
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Pembayaran yang sudah divalidasi tidak dapat diedit.');
        }

        $payment->load(['student', 'bill']);
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Only allow updating pending payments
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Pembayaran yang sudah divalidasi tidak dapat diedit.');
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,transfer,e-wallet,cheque,other',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
            'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update payment
        $payment->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        // Update payment slip if new file uploaded
        if ($request->hasFile('payment_slip')) {
            $file = $request->file('payment_slip');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('payment_slips', $fileName, 'public');

            // Delete old file
            if ($payment->paymentSlips->isNotEmpty()) {
                $payment->paymentSlips->first()->deleteFile();
                $payment->paymentSlips->first()->delete();
            }

            // Create new payment slip record
            PaymentSlip::create([
                'payment_id' => $payment->id,
                'bill_id' => $payment->bill_id,
                'student_id' => $payment->student_id,
                'uploaded_by' => auth()->id(),
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_hash' => hash_file('sha256', $file->getPathname()),
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only allow deleting pending payments
        if ($payment->status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Pembayaran yang sudah divalidasi tidak dapat dihapus.');
        }

        // Delete payment slips
        foreach ($payment->paymentSlips as $slip) {
            $slip->delete();
        }

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Display validation queue for finance users.
     */
    public function validationQueue()
    {
        $this->authorize('viewValidationQueue', Payment::class);

        $pendingPayments = Payment::with(['student', 'bill.paymentType', 'paymentSlips'])
            ->pending()
            ->latest()
            ->paginate(20);

        return view('payments.validation-queue', compact('pendingPayments'));
    }

    /**
     * Validate a payment.
     */
    public function validate(Request $request, Payment $payment)
    {
        $this->authorize('validate', Payment::class);

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'receipt_number' => 'required_if:action,approve|string|max:50',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        if ($request->action === 'approve') {
            $payment->validate(auth()->user(), $request->receipt_number);
            
            // Update bill status
            $payment->bill->updateStatus();
            
            return back()->with('success', 'Pembayaran berhasil divalidasi.');
        } else {
            $payment->reject(auth()->user(), $request->rejection_reason);
            return back()->with('success', 'Pembayaran ditolak.');
        }
    }

    /**
     * Bulk upload interface for teachers.
     */
    public function bulkUpload()
    {
        $this->authorize('bulkUpload', Payment::class);

        $classes = \App\Models\ClassRoom::active()->get();
        return view('payments.bulk-upload', compact('classes'));
    }

    /**
     * Process bulk upload.
     */
    public function processBulkUpload(Request $request)
    {
        $this->authorize('bulkUpload', Payment::class);

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:class_rooms,id',
            'payment_slips.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $class = \App\Models\ClassRoom::findOrFail($request->class_id);
        $uploadedCount = 0;

        if ($request->hasFile('payment_slips')) {
            foreach ($request->file('payment_slips') as $file) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('payment_slips', $fileName, 'public');

                PaymentSlip::create([
                    'bill_id' => null, // Will be linked later
                    'student_id' => null, // Will be identified from filename or manual matching
                    'uploaded_by' => auth()->id(),
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'file_hash' => hash_file('sha256', $file->getPathname()),
                    'metadata' => ['class_id' => $class->id],
                ]);

                $uploadedCount++;
            }
        }

        return back()->with('success', "Berhasil mengunggah {$uploadedCount} bukti pembayaran. Silakan lakukan matching manual di halaman validasi.");
    }
}
