<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BosBudget;
use App\Models\BosTransaction;
use App\Models\ClassRoom;
use App\Models\Payment;
use App\Models\Proposal;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Teacher Dashboard
        if ($user->isA('teacher')) {
            return $this->teacherDashboard();
        }

        // Executive Dashboard (Principal & Foundation)
        if ($user->isA('principal') || $user->isA('foundation')) {
            return $this->executiveDashboard();
        }

        // Admin & Finance Dashboard (Default Full View)
        return $this->adminDashboard();
    }

    /**
     * Teacher Dashboard - Simplified view
     */
    private function teacherDashboard()
    {
        // Basic stats
        $stats = [
            'total_students' => Student::active()->count(),
            'unpaid_bills' => Bill::unpaid()->count(),
            'total_arrears' => Bill::whereIn('status', ['unpaid', 'partial'])->sum('final_amount'),
        ];

        // Recent payments by this teacher
        $recentPayments = Payment::with(['student', 'bill.paymentType'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Students with arrears
        $studentsWithArrears = Bill::with(['student.class', 'paymentType'])
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        return view('home-teacher', compact('stats', 'recentPayments', 'studentsWithArrears'));
    }

    /**
     * Executive Dashboard (Principal & Foundation)
     * Focus on Monitoring & Approvals
     */
    private function executiveDashboard()
    {
        // Basic stats
        $stats = [
            'total_students' => Student::active()->count(),
            'total_classes' => ClassRoom::active()->count(),
            'total_income' => Payment::where('status', 'approved')->sum('amount'),
        ];

        // BOS Overview
        $currentYear = date('Y');
        $currentBudget = BosBudget::where('year', $currentYear)->first();
        $bosStats = [
            'budget_year' => $currentYear,
            'budget_total' => $currentBudget->amount ?? 0,
            'budget_remaining' => $currentBudget->remaining ?? 0,
            'percentage' => $currentBudget ? $currentBudget->progress_percentage : 0,
        ];

        // Proposal stats (Critical for approval)
        $pendingProposals = Proposal::with('submitter')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get(); // Get all pending for review

        // Financial Summary (Monthly Approved Income)
        $monthlyIncome = Payment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'approved')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('home-executive', compact('stats', 'bosStats', 'pendingProposals', 'monthlyIncome'));
    }

    /**
     * Admin & Finance Dashboard - Full Operational View
     */
    private function adminDashboard()
    {
        // Basic stats
        $stats = [
            'total_students' => Student::active()->count(),
            'total_classes' => ClassRoom::active()->count(),
            'total_users' => User::count(),
            'unpaid_bills' => Bill::unpaid()->count(),
            'overdue_bills' => Bill::overdue()->count(),
            'total_bills_amount' => Bill::unpaid()->sum('final_amount'),
        ];

        // Payment stats
        $paymentStats = [
            'total_payments' => Payment::where('status', 'approved')->count(),
            'total_income' => Payment::where('status', 'approved')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
        ];

        // BOS stats
        $currentYear = date('Y');
        $currentBudget = BosBudget::where('year', $currentYear)->first();
        $bosStats = [
            'current_budget' => $currentBudget->amount ?? 0,
            'budget_used' => $currentBudget->used ?? 0,
            'budget_remaining' => $currentBudget->remaining ?? 0,
            'total_transactions' => BosTransaction::count(),
        ];

        // Proposal stats
        $proposalStats = [
            'pending_proposals' => Proposal::where('status', 'pending')->count(),
            'approved_proposals' => Proposal::where('status', 'approved')->count(),
            'total_proposal_amount' => Proposal::where('status', 'pending')->sum('amount'),
        ];

        // Recent data
        $recentStudents = Student::with('class')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $overduePayments = Bill::with(['student.class', 'paymentType'])
            ->overdue()
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        $recentPayments = Payment::with(['student', 'bill.paymentType'])
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $pendingProposals = Proposal::with('submitter')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact(
            'stats',
            'paymentStats',
            'bosStats',
            'proposalStats',
            'recentStudents',
            'overduePayments',
            'recentPayments',
            'pendingProposals'
        ));
    }
}
