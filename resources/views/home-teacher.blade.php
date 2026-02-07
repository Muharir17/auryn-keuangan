@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard - Guru/Wali Kelas</h1>
@stop

@section('content')
{{-- Row 1: Basic Stats --}}
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_students'] }}</h3>
                <p>Total Siswa</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('students.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['unpaid_bills'] }}</h3>
                <p>Tagihan Belum Lunas</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <a href="{{ route('arrears.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp {{ number_format($stats['total_arrears'] / 1000000, 1) }}M</h3>
                <p>Total Tunggakan</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('arrears.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Row 2: Quick Actions --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('payments.create') }}" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-credit-card"></i> Input Pembayaran
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('payments.bulk-upload') }}" class="btn btn-info btn-lg btn-block">
                            <i class="fas fa-upload"></i> Upload Massal
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('arrears.index') }}" class="btn btn-warning btn-lg btn-block">
                            <i class="fas fa-exclamation-circle"></i> Lihat Tunggakan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Recent Data --}}
<div class="row">
    {{-- Recent Payments by This Teacher --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-credit-card"></i> Pembayaran yang Saya Input</h3>
                <div class="card-tools">
                    <a href="{{ route('payments.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                <td>{{ $payment->student->name }}</td>
                                <td>{{ $payment->bill->paymentType->name }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($payment->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pembayaran yang diinput</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Students with Arrears --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Siswa dengan Tunggakan</h3>
                <div class="card-tools">
                    <a href="{{ route('arrears.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentsWithArrears as $bill)
                            <tr class="{{ $bill->is_overdue ? 'table-danger' : '' }}">
                                <td>{{ $bill->student->name }}</td>
                                <td>{{ $bill->student->class->name }}</td>
                                <td>{{ $bill->paymentType->name }}</td>
                                <td>Rp {{ number_format($bill->final_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($bill->is_overdue)
                                        <strong class="text-danger">{{ $bill->due_date->format('d/m/Y') }}</strong>
                                    @else
                                        {{ $bill->due_date->format('d/m/Y') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-success">
                                    <i class="fas fa-check-circle"></i> Tidak ada tunggakan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Info Box --}}
<div class="row">
    <div class="col-md-12">
        <div class="callout callout-info">
            <h5><i class="fas fa-info-circle"></i> Informasi:</h5>
            <p>Sebagai Guru/Wali Kelas, Anda dapat:</p>
            <ul>
                <li>Melihat data siswa</li>
                <li>Input pembayaran siswa</li>
                <li>Upload pembayaran secara massal</li>
                <li>Melihat tunggakan siswa</li>
            </ul>
            <p class="mb-0">Untuk validasi pembayaran, silakan hubungi Bendahara.</p>
        </div>
    </div>
</div>
@stop