@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
{{-- Row 1: Basic Stats --}}
<div class="row">
    <div class="col-lg-3 col-6">
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

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_classes'] }}</h3>
                <p>Total Kelas</p>
            </div>
            <div class="icon">
                <i class="fas fa-school"></i>
            </div>
            <a href="{{ route('classes.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['unpaid_bills'] }}</h3>
                <p>Tagihan Belum Lunas</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <a href="{{ route('bills.index') }}?status=unpaid" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['overdue_bills'] }}</h3>
                <p>Tagihan Terlambat</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('bills.index') }}?status=overdue" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Row 2: Payment & BOS Stats --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box bg-gradient-success">
            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pemasukan</span>
                <span class="info-box-number">Rp {{ number_format($paymentStats['total_income'], 0, ',', '.') }}</span>
                <span class="progress-description">{{ $paymentStats['total_payments'] }} pembayaran</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box bg-gradient-warning">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pembayaran Pending</span>
                <span class="info-box-number">Rp
                    {{ number_format($paymentStats['pending_amount'], 0, ',', '.') }}</span>
                <span class="progress-description">{{ $paymentStats['pending_payments'] }} pending</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box bg-gradient-primary">
            <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Budget BOS {{ date('Y') }}</span>
                <span class="info-box-number">Rp {{ number_format($bosStats['current_budget'], 0, ',', '.') }}</span>
                <span class="progress-description">Sisa: Rp
                    {{ number_format($bosStats['budget_remaining'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box bg-gradient-info">
            <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Proposal Pending</span>
                <span class="info-box-number">{{ $proposalStats['pending_proposals'] }}</span>
                <span class="progress-description">Rp
                    {{ number_format($proposalStats['total_proposal_amount'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Charts & Recent Data --}}
<div class="row">
    {{-- Recent Payments --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-credit-card"></i> Pembayaran Terbaru</h3>
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
                            <th>Siswa</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->student->name }}</td>
                                <td>{{ $payment->bill->paymentType->name }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pending Proposals --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt"></i> Proposal Menunggu Approval</h3>
                <div class="card-tools">
                    <a href="{{ route('proposals.approval') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Pengaju</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingProposals as $proposal)
                            <tr>
                                <td>{{ Str::limit($proposal->title, 30) }}</td>
                                <td>{{ $proposal->submitter->name }}</td>
                                <td>Rp {{ number_format($proposal->amount, 0, ',', '.') }}</td>
                                <td>{{ $proposal->date->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada proposal pending</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 4: Recent Students & Overdue Payments --}}
<div class="row">
    {{-- Recent Students --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-graduate"></i> Siswa Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('students.index') }}" class="btn btn-tool btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                            <tr>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->class->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $student->is_active ? 'success' : 'danger' }}">
                                        {{ $student->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada siswa</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Overdue Payments --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Tagihan Terlambat</h3>
                <div class="card-tools">
                    <a href="{{ route('bills.index') }}?status=overdue" class="btn btn-tool btn-sm">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Jatuh Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overduePayments as $bill)
                            <tr>
                                <td>{{ $bill->student->name }}</td>
                                <td>{{ $bill->paymentType->name }}</td>
                                <td>Rp {{ number_format($bill->final_amount, 0, ',', '.') }}</td>
                                <td class="text-danger">
                                    <strong>{{ $bill->due_date->format('d/m/Y') }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-success">Tidak ada tagihan terlambat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Row 5: Quick Actions --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus"></i> Tambah Siswa
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('bills.create') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-file-invoice"></i> Buat Tagihan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('payments.create') }}" class="btn btn-success btn-block">
                            <i class="fas fa-credit-card"></i> Input Pembayaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.financial') }}" class="btn btn-info btn-block">
                            <i class="fas fa-chart-line"></i> Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop