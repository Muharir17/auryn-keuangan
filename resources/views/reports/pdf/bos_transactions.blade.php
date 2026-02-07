<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi BOS</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 10px; }
        table th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; color: white; font-size: 9px; }
        .badge-income { background-color: #28a745; }
        .badge-expense { background-color: #dc3545; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI BOS</h1>
        <p>SMP ASM</p>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="8%">Tahun</th>
                <th width="30%">Deskripsi</th>
                <th width="12%">Kategori</th>
                <th width="10%" class="text-center">Tipe</th>
                <th width="15%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalIncome = 0;
                $totalExpense = 0;
            @endphp
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $transaction->date->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $transaction->budget->year }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->category ?? '-' }}</td>
                    <td class="text-center">
                        @if($transaction->type == 'income')
                            <span class="badge badge-income">Pemasukan</span>
                            @php $totalIncome += $transaction->amount; @endphp
                        @else
                            <span class="badge badge-expense">Pengeluaran</span>
                            @php $totalExpense += $transaction->amount; @endphp
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                </tr>
            @endforelse
            @if($transactions->count() > 0)
                <tr style="font-weight: bold; background-color: #e8f5e9;">
                    <td colspan="6" class="text-right">TOTAL PEMASUKAN:</td>
                    <td class="text-right">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #ffebee;">
                    <td colspan="6" class="text-right">TOTAL PENGELUARAN:</td>
                    <td class="text-right">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td colspan="6" class="text-right">SALDO:</td>
                    <td class="text-right">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Keuangan SMP ASM</p>
        <p>Total Data: {{ $transactions->count() }} transaksi</p>
    </div>
</body>
</html>
