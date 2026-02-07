<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary {
            margin: 20px 0;
        }

        .summary-box {
            display: inline-block;
            width: 30%;
            padding: 15px;
            margin: 5px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .summary-box h3 {
            margin: 0;
            font-size: 24px;
            color: #28a745;
        }

        .summary-box p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>SMP ASM - Tahun {{ $year }}</p>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
            <p>Total Pemasukan</p>
        </div>
        <div class="summary-box">
            <h3>Rp {{ number_format($totalBills, 0, ',', '.') }}</h3>
            <p>Total Tagihan</p>
        </div>
        <div class="summary-box">
            <h3>Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
            <p>Pembayaran Pending</p>
        </div>
    </div>

    <h3>Rincian Bulanan</h3>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="text-right">Total Pemasukan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            @endphp
            @forelse($monthlyData as $data)
                <tr>
                    <td>{{ $months[$data->month] }}</td>
                    <td class="text-right">Rp {{ number_format($data->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
            <tr style="font-weight: bold; background-color: #f8f9fa;">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Keuangan SMP ASM</p>
    </div>
</body>

</html>