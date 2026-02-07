<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;

class ArrearController extends Controller
{
    /**
     * Display arrears (tunggakan)
     */
    public function index(Request $request)
    {
        $query = Bill::with(['student.class', 'paymentType'])
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderBy('due_date', 'asc');

        // Filters
        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('overdue_only') && $request->overdue_only == '1') {
            $query->where('due_date', '<', now());
        }

        $arrears = $query->paginate(20);

        // Summary
        $totalArrears = Bill::whereIn('status', ['unpaid', 'partial'])->sum('final_amount');
        $totalOverdue = Bill::whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', now())
            ->sum('final_amount');
        $studentsWithArrears = Bill::whereIn('status', ['unpaid', 'partial'])
            ->distinct('student_id')
            ->count('student_id');

        $classes = ClassRoom::where('is_active', true)->get();
        $students = Student::active()->get();

        return view('arrears.index', compact(
            'arrears',
            'totalArrears',
            'totalOverdue',
            'studentsWithArrears',
            'classes',
            'students'
        ));
    }
}
