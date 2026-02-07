<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Tagihan</title>
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
        <h1>LAPORAN TAGIHAN</h1>
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
                <th width="18%">Jenis Tagihan</th>
                <th width="15%" class="text-right">Jumlah</th>
                <th width="10%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                                $totalPaid = 0;
                $totalUnpaid = 0;
            @endphp
        @forelse($bills as $index => $bill)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $bill->created_at->format('d/m/Y') }}</td>
                <td>{{ $bill->student->name }}</td>
                <td>{{ $bill->student->class->name }}</td>
                <td>{{ $bill->paymentType->name }}</td>
            <td class="text-right">Rp {{ number_format($bill->amount, 0, ',', '.') }}</td>
            <td class="text-center">
                    @if($bill->status == 'paid')
                        <span class="badge badge-success">Lunas</span>
                        @php $totalPaid += $bill->amount; @endphp
                    @elseif($bill->status == 'partial')
                        <span class="badge badge-warning">Sebagian</span>
                        @php $totalUnpaid += $bill->amount; @endphp
                    @else
                        <span class="badge badge-danger">Belum Bayar</span>
                        @php $totalUnpaid += $bill->amount; @endphp
                    @endif
                </td>
            </tr>
        @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data tagihan</td>
            </tr>
        @endforelse
        @if($bills->count() > 0)
            <tr style="font-weight: bold; background-color: #e8f5e9;">
                <td colspan="5" class="text-right">TOTAL LUNAS:</td>
                <td class="text-right">Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr style="font-weight: bold; background-color: #ffebee;">
                <td colspan="5" class="text-right">TOTAL BELUM LUNAS:</td>
                <td class="text-right">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</td>
                <td></td>
            </tr>
            <tr style="font-weight: bold; background-color: #f8f9fa;">
                <td colspan="5" class="text-right">GRAND TOTAL:</td>
                    <td class="text-right">Rp {{ number_format($totalPaid + $totalUnpaid, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
        @endif
        </tbody>
    </table>

    <div class="footer">
       
 <p>Laporan ini digenerate otomatis oleh Sistem Keuangan SMP ASM</p>
        <p>Total Data: {{ $bills->count() }} tagihan</p>
    </div>
</body>
</html>
