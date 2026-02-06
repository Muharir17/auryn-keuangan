<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_students' => Student::active()->count(),
            'total_classes' => ClassRoom::active()->count(),
            'total_users' => User::count(),
            'unpaid_bills' => Bill::unpaid()->count(),
            'overdue_bills' => Bill::overdue()->count(),
            'total_bills_amount' => Bill::unpaid()->sum('final_amount'),
        ];

        $recentStudents = Student::with('class')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $overduePayments = Bill::with(['student.class', 'paymentType'])
            ->overdue()
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        return view('home', compact('stats', 'recentStudents', 'overduePayments'));
    }
}
