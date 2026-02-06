<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\ClassRoom;
use App\Models\PaymentType;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with(['student.class', 'paymentType']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('payment_type_id')) {
            $query->where('payment_type_id', $request->payment_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $bills = $query->orderBy('due_date', 'desc')->paginate(20);
        $paymentTypes = PaymentType::active()->get();
        $classes = ClassRoom::active()->get();

        return view('bills.index', compact('bills', 'paymentTypes', 'classes'));
    }

    public function create()
    {
        $students = Student::active()->with('class')->orderBy('name')->get();
        $paymentTypes = PaymentType::active()->orderBy('sort_order')->get();

        return view('bills.create', compact('students', 'paymentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['final_amount'] = $validated['amount'] - $validated['discount'];
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'unpaid';

        Bill::create($validated);

        return redirect()->route('bills.index')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function show(Bill $bill)
    {
        $bill->load(['student.class', 'paymentType', 'payments', 'creator']);

        return view('bills.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        $students = Student::active()->with('class')->orderBy('name')->get();
        $paymentTypes = PaymentType::active()->orderBy('sort_order')->get();

        return view('bills.edit', compact('bill', 'students', 'paymentTypes'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['final_amount'] = $validated['amount'] - $validated['discount'];

        $bill->update($validated);

        return redirect()->route('bills.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function generateMonthly(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'payment_type_id' => 'required|exists:payment_types,id',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $paymentType = PaymentType::findOrFail($validated['payment_type_id']);

        $studentsQuery = Student::active();

        if ($request->filled('class_id')) {
            $studentsQuery->where('class_id', $validated['class_id']);
        }

        $students = $studentsQuery->get();
        $count = 0;

        foreach ($students as $student) {
            $exists = Bill::where('student_id', $student->id)
                ->where('payment_type_id', $paymentType->id)
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->exists();

            if (!$exists) {
                Bill::create([
                    'student_id' => $student->id,
                    'payment_type_id' => $paymentType->id,
                    'month' => $validated['month'],
                    'year' => $validated['year'],
                    'amount' => $paymentType->default_amount,
                    'discount' => 0,
                    'final_amount' => $paymentType->default_amount,
                    'due_date' => date('Y-m-10', strtotime("{$validated['year']}-{$validated['month']}-01")),
                    'status' => 'unpaid',
                    'created_by' => Auth::id(),
                ]);
                $count++;
            }
        }

        return redirect()->route('bills.index')
            ->with('success', "Berhasil membuat {$count} tagihan.");
    }
}
