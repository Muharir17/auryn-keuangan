<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('class');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(20);
        $classes = ClassRoom::active()->orderBy('name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::active()->orderBy('name')->get();

        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:students,nis',
            'nisn' => 'nullable|string|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email',
            'enrollment_date' => 'nullable|date',
            'status' => 'required|in:active,graduated,transferred,dropped',
            'notes' => 'nullable|string',
        ]);

        $student = Student::create($validated);

        $student->class->increment('student_count');

        return redirect()->route('students.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        $student->load(['class', 'bills.paymentType', 'payments']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::active()->orderBy('name')->get();

        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'nisn' => 'nullable|string|unique:students,nisn,' . $student->id,
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:L,P',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email',
            'enrollment_date' => 'nullable|date',
            'status' => 'required|in:active,graduated,transferred,dropped',
            'notes' => 'nullable|string',
        ]);

        $oldClassId = $student->class_id;
        $student->update($validated);

        if ($oldClassId != $validated['class_id']) {
            ClassRoom::find($oldClassId)->decrement('student_count');
            ClassRoom::find($validated['class_id'])->increment('student_count');
        }

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->bills()->count() > 0) {
            return redirect()->route('students.index')
                ->with('error', 'Tidak dapat menghapus siswa yang sudah memiliki tagihan.');
        }

        $classId = $student->class_id;
        $student->delete();

        ClassRoom::find($classId)->decrement('student_count');

        return redirect()->route('students.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }

    public function paymentHistory(Student $student)
    {
        $student->load(['payments.bill.paymentType', 'payments.validator']);

        return view('students.payment-history', compact('student'));
    }

    public function arrearsDetail(Student $student)
    {
        $student->load(['arrears.bill.paymentType', 'arrears.adjustments']);

        return view('students.arrears-detail', compact('student'));
    }

    /**
     * Get bills for a student (API endpoint).
     */
    public function getBills(Student $student)
    {
        $bills = \App\Models\Bill::with('paymentType')
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'payment_type' => [
                        'name' => $bill->paymentType->name
                    ],
                    'due_date' => $bill->due_date->format('d M Y'),
                    'amount' => $bill->amount,
                    'amount_formatted' => 'Rp ' . number_format($bill->amount, 0, ',', '.')
                ];
            });

        return response()->json($bills);
    }
}
