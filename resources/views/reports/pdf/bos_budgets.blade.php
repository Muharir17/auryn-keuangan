<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Budget BOS</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .progress-text { font-size: 10px; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BUDGET BOS</h1>
        <p>SMP ASM</p>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Tahun</th>
                <th width="20%" class="text-right">Total Budget</th>
                <th width="20%" class="text-right">Terpakai</th>
                <th width="20%" class="text-right">Sisa</th>
                <th width="15%" class="text-center">Progress</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalBudget = 0;
                $totalUsed = 0;
                $totalRemaining = 0;
            @endphp
            @forelse($budgets as $budget)
                @php
                    $totalBudget += $budget->amount;
                    $totalUsed += $budget->used;
                    $totalRemaining += $budget->remaining;
                    $percentage = $budget->progress_percentage;
                @endphp
                <tr>
                    <td class="text-center"><strong>{{ $budget->year }}</strong></td>
                    <td class="text-right">Rp {{ number_format($budget->amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($budget->used, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($budget->remaining, 0, ',', '.') }}</td>
                    <td class="text-center progress-text">{{ number_format($percentage, 1) }}%</td>
                    <td>{{ $budget->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data budget</td>
                </tr>
            @endforelse
            @if($budgets->count() > 0)
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td class="text-right">TOTAL:</td>
                    <td class="text-right">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalUsed, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Keuangan SMP ASM</p>
        <p>Total Budget: {{ $budgets->count() }} tahun</p>
    </div>
</body>
</html>
