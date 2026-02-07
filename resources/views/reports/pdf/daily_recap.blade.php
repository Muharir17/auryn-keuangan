<!DOCTYPE html>
<html>

<head>
    <title>Laporan Harian Kasir</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
        }

        th {
            background-color: #eee;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
        }

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-box-left {
            float: left;
            width: 200px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>SMP AL-AZHAR SYIFA BUDI</h2>
        <p>LAPORAN PENERIMAAN KAS HARIAN</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px">No</th>
                <th>No. Kwitansi</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Keterangan Pembayaran</th>
                <th>Penerima</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($payments as $index => $payment)
                @php $total += $payment->amount; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $payment->receipt_number ?? $payment->payment_number }}</td>
                    <td>{{ $payment->student->name }}</td>
                    <td class="text-center">{{ $payment->student->class->name ?? '-' }}</td>
                    <td>{{ $payment->bill->paymentType->name }} - {{ $payment->bill->month }} {{ $payment->bill->year }}
                    </td>
                    <td class="text-center">{{ $payment->validator->name ?? ($payment->uploader->name ?? '-') }}</td>
                    <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada penerimaan kas pada tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>TOTAL PENERIMAAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature-box-left">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah / Yayasan</p>
            <br><br><br>
            <p>( .................................... )</p>
        </div>
        <div class="signature-box">
            <p>Jakarta, {{ date('d M Y') }}</p>
            <p>Bendahara / Kasir</p>
            <br><br><br>
            <p>( {{ auth()->user()->name }} )</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>

</html>