<?php

namespace App\Http\Controllers;

use App\Models\BosBudget;
use App\Models\BosTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BosController extends Controller
{
    /**
     * Display BOS budgets
     */
    public function budgets()
    {
        $budgets = BosBudget::orderBy('year', 'desc')->get();

        return view('bos.budgets', compact('budgets'));
    }

    /**
     * Display BOS transactions
     */
    public function transactions()
    {
        $transactions = BosTransaction::with('budget')
            ->orderBy('date', 'desc')
            ->get();

        return view('bos.transactions', compact('transactions'));
    }

    /**
     * Export BOS Budgets to PDF
     */
    public function budgetsPdf()
    {
        $budgets = BosBudget::orderBy('year', 'desc')->get();

        $pdf = Pdf::loadView('reports.pdf.bos_budgets', compact('budgets'));
        return $pdf->download('laporan-budget-bos-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export BOS Transactions to PDF
     */
    public function transactionsPdf(Request $request)
    {
        $query = BosTransaction::with('budget');

        if ($request->filled('year')) {
            $query->whereHas('budget', fn($q) => $q->where('year', $request->year));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('reports.pdf.bos_transactions', compact('transactions'));
        return $pdf->download('laporan-transaksi-bos-' . date('Y-m-d') . '.pdf');
    }
}
