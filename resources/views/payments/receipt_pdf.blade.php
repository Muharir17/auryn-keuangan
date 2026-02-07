<!DOCTYPE html>
<html>

<head>
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        .container {
            width: 100%;
            border: 1px solid #000;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 5px 0 0;
        }

        .content {
            margin-bottom: 30px;
        }

        .row {
            margin-bottom: 10px;
            display: table;
            width: 100%;
        }

        .label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }

        .value {
            display: table-cell;
            border-bottom: 1px dotted #000;
        }

        .amount-box {
            border: 2px solid #000;
            padding: 10px;
            font-weight: bold;
            font-size: 18px;
            float: left;
            margin-top: 20px;
            background-color: #eee;
        }

        .signature {
            float: right;
            text-align: center;
            margin-top: 10px;
            width: 200px;
        }

        .footer {
            clear: both;
            margin-top: 60px;
            font-size: 10px;
            font-style: italic;
        }

        .watermark {
            position: absolute;
            top: 30%;
            left: 25%;
            font-size: 80px;
            opacity: 0.1;
            transform: rotate(-30deg);
            z-index: -1;
        }
    </style>
</head>

<body>
    @php
        function terbilang($nilai)
        {
            $nilai = abs($nilai);
            $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[$nilai];
            } else if ($nilai < 20) {
                $temp = terbilang($nilai - 10) . " Belas";
            } else if ($nilai < 100) {
                $temp = terbilang($nilai / 10) . " Puluh" . terbilang($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " Seratus" . terbilang($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = terbilang($nilai / 100) . " Ratus" . terbilang($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " Seribu" . terbilang($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = terbilang($nilai / 1000) . " Ribu" . terbilang($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = terbilang($nilai / 1000000) . " Juta" . terbilang($nilai % 1000000);
            }
            return $temp;
        }
    @endphp

    <div class="container">
        @if($payment->status != 'approved' && $payment->status != 'validated')
            <div class="watermark">BELUM LUNAS</div>
        @endif

        <div class="header">
            <h2>SMP AL-AZHAR SYIFA BUDI</h2>
            <p>Jl. Kemang Raya No. 7, Jakarta Selatan - Telp. (021) 71793333</p>
            <p>KWITANSI PEMBAYARAN</p>
        </div>

        <div class="content">
            <div class="row">
                <div class="label">No. Kwitansi</div>
                <div class="value">: {{ $payment->receipt_number ?? $payment->payment_number }}</div>
            </div>
            <div class="row">
                <div class="label">Telah terima dari</div>
                <div class="value">: <strong>{{ $payment->student->name }}</strong> (Kelas
                    {{ $payment->student->class->name ?? '-' }})</div>
            </div>
            <div class="row">
                <div class="label">Uang Sejumlah</div>
                <div class="value">: <i>{{ trim(terbilang($payment->amount)) }} Rupiah</i></div>
            </div>
            <div class="row">
                <div class="label">Untuk Pembayaran</div>
                <div class="value">: {{ $payment->bill->paymentType->name }} - {{ $payment->bill->month }}
                    {{ $payment->bill->year }}</div>
            </div>
            <div class="row">
                <div class="label">Catatan</div>
                <div class="value">: {{ $payment->notes ?? '-' }}</div>
            </div>
        </div>

        <div class="amount-box">
            Rp {{ number_format($payment->amount, 0, ',', '.') }},-
        </div>

        <div class="signature">
            <p>Jakarta, {{ $payment->payment_date->format('d M Y') }}</p>
            <br><br><br>
            <p><u>{{ $payment->validator->name ?? ($payment->uploader->name ?? 'Petugas Keuangan') }}</u></p>
            <p>Bendahara / Tata Usaha</p>
        </div>

        <div class="footer">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | ID: {{ $payment->payment_number }}
        </div>
    </div>
</body>

</html>