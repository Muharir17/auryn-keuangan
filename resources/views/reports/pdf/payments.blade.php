<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 9px;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN PEMBAYARAN</h1>
        <p>SMP ASM</p>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="20%">Siswa</th>
                <th width="10%">Kelas</th>
                <th width="18%">Jenis Pembayaran</th>
                <th width="15%" class="text-right">Jumlah</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($payments as $index => $payment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                    <td>{{ $payment->student->name }}</td>
                    <td>{{ $payment->student->class->name }}</td>
                    <td>{{ $payment->bill->paymentType->name }}</td>
                    <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($payment->status == 'approved')
                            <span class="badge badge-success">Approved</span>
                            @php $total += $payment->amount; @endphp
                        @elseif($payment->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Rejected</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pembayaran</td>
                </tr>
            @endforelse
            @if($payments->count() > 0)
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td colspan="5" class="text-right">TOTAL (Approved):</td>
                    <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate otomatis oleh Sistem Keuangan SMP ASM</p>
        <p>Total Data: {{ $payments->count() }} pembayaran</p>
    </div>
</body>

</html>