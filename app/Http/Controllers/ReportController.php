<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Laporan Keuangan
     */
    public function financial(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        // Summary data
        $totalIncome = Payment::where('status', 'approved')
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->when($month, fn($q) => $q->whereRaw('MONTH(created_at) = ?', [$month]))
            ->sum('amount');

        $totalBills = Bill::whereRaw('YEAR(created_at) = ?', [$year])
            ->when($month, fn($q) => $q->whereRaw('MONTH(created_at) = ?', [$month]))
            ->sum('amount');

        $totalPending = Payment::where('status', 'pending')
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->when($month, fn($q) => $q->whereRaw('MONTH(created_at) = ?', [$month]))
            ->sum('amount');

        // Monthly breakdown
        $monthlyData = Payment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'approved')
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.financial', compact('totalIncome', 'totalBills', 'totalPending', 'monthlyData', 'year', 'month'));
    }

    /**
     * Laporan Pembayaran
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['student.class', 'bill.paymentType']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(20);

        $classes = \App\Models\ClassRoom::where('is_active', true)->get();

        return view('reports.payments', compact('payments', 'classes'));
    }

    /**
     * Export Laporan Keuangan ke PDF
     */
    public function financialPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');

        $totalIncome = Payment::where('status', 'approved')
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->when($month, fn($q) => $q->whereRaw('MONTH(created_at) = ?', [$month]))
            ->sum('amount');

        $totalBills = Bill::whereRaw('YEAR(created_at) = ?', [$year])
            ->when($month, fn($q) => $q->whereRaw('MONTH(created_at) = ?', [$month]))
            ->sum('amount');

        $totalPending = Payment::where('status', 'pending')
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->when($month, fn($q) => $q->whereRaw('MONTH(created_at) = ?', [$month]))
            ->sum('amount');

        $monthlyData = Payment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'approved')
            ->whereRaw('YEAR(created_at) = ?', [$year])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.financial', compact('totalIncome', 'totalBills', 'totalPending', 'monthlyData', 'year', 'month'));
        return $pdf->download('laporan-keuangan-' . $year . '.pdf');
    }

    /**
     * Export Laporan Pembayaran ke PDF
     */
    public function paymentsPdf(Request $request)
    {
        $query = Payment::with(['student.class', 'bill.paymentType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->latest()->get();

        $pdf = Pdf::loadView('reports.pdf.payments', compact('payments'));
        return $pdf->download('laporan-pembayaran-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Tagihan ke PDF
     */
    public function billsPdf(Request $request)
    {
        $query = Bill::with(['student.class', 'paymentType']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }

        $bills = $query->latest()->get();

        $pdf = Pdf::loadView('reports.pdf.bills', compact('bills'));
        return $pdf->download('laporan-tagihan-' . date('Y-m-d') . '.pdf');
    }
}
