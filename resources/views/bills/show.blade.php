@extends('adminlte::page')

@section('title', 'Detail Tagihan - ' . $bill->bill_number)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Tagihan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bills.index') }}">Tagihan</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Informasi Tagihan</h3>
                    <div class="card-tools">
                        <a href="{{ route('bills.edit', $bill) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $bill->bill_number }}</h3>
                    <p class="text-muted text-center">{{ $bill->paymentType->name ?? '-' }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Siswa</b> 
                            <span class="float-right">
                                <a href="{{ route('students.show', $bill->student) }}">{{ $bill->student->name ?? '-' }}</a>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Kelas</b> <span class="float-right">{{ $bill->student->class->name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Periode</b> 
                            <span class="float-right">
                                @if($bill->month)
                                    {{ sprintf('%02d', $bill->month) }}/{{ $bill->year }}
                                @else
                                    {{ $bill->year }}
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Jumlah</b> <span class="float-right">Rp {{ number_format($bill->amount, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Diskon</b> <span class="float-right">Rp {{ number_format($bill->discount, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Total Bayar</b> <span class="float-right"><strong>Rp {{ number_format($bill->final_amount, 0, ',', '.') }}</strong></span>
                        </li>
                        <li class="list-group-item">
                            <b>Jatuh Tempo</b> 
                            <span class="float-right">
                                @if($bill->is_overdue)
                                    <span class="text-danger">{{ $bill->due_date->format('d/m/Y') }} (Terlambat)</span>
                                @else
                                    {{ $bill->due_date->format('d/m/Y') }}
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right">
                                @if($bill->status == 'paid')
                                    <span class="badge badge-success">Lunas</span>
                                @elseif($bill->status == 'partial')
                                    <span class="badge badge-warning">Sebagian</span>
                                @else
                                    <span class="badge badge-danger">Belum Lunas</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Sudah Dibayar</b> <span class="float-right">Rp {{ number_format($bill->paid_amount ?? 0, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Sisa</b> <span class="float-right">Rp {{ number_format($bill->remaining_amount ?? $bill->final_amount, 0, ',', '.') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if($bill->notes)
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Catatan</h3>
                </div>
                <div class="card-body">
                    <p>{{ $bill->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pembayaran</h3>
                </div>
                <div class="card-body">
                    @if($bill->payments && $bill->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Divalidasi Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date?->format('d/m/Y H:i') ?? '-' }}</td>
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
                                            <td>{{ $payment->validator?->name ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada pembayaran untuk tagihan ini.</p>
                            <p class="text-muted">Modul pembayaran akan segera diimplementasikan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pembuat</h3>
                </div>
                <div class="card-body">
                    <p><strong>Dibuat oleh:</strong> {{ $bill->creator->name ?? 'System' }}</p>
                    <p><strong>Tanggal Dibuat:</strong> {{ $bill->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Terakhir Diupdate:</strong> {{ $bill->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@stop
