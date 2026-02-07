@extends('adminlte::page')

@section('title', 'Riwayat Pembayaran - ' . $student->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Riwayat Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Siswa</a></li>
                <li class="breadcrumb-item active">Riwayat Pembayaran</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pembayaran - {{ $student->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.show', $student) }}" class="btn btn-default btn-sm mr-2">
                            <i class="fas fa-arrow-left"></i> Kembali ke Detail
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>NIS:</strong> {{ $student->nis }}</p>
                            <p><strong>Nama:</strong> {{ $student->name }}</p>
                            <p><strong>Kelas:</strong> {{ $student->class->name ?? '-' }}</p>
                        </div>
                    </div>

                    @if($student->payments && $student->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Bayar</th>
                                        <th>No Tagihan</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Divalidasi Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->payments as $index => $payment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $payment->payment_date?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>{{ $payment->bill->bill_number ?? '-' }}</td>
                                            <td>{{ $payment->bill->paymentType->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>{{ $payment->payment_method ?? '-' }}</td>
                                            <td>
                                                @if($payment->status == 'validated')
                                                    <span class="badge badge-success">Tervalidasi</span>
                                                @elseif($payment->status == 'pending')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->validator->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada riwayat pembayaran.</p>
                            <p class="text-muted">Modul pembayaran akan segera diimplementasikan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
