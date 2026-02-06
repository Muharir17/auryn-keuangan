@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
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

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-graduate mr-1"></i>
                        Siswa Terbaru
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentStudents as $student)
                                    <tr>
                                        <td>{{ $student->nis }}</td>
                                        <td>
                                            <a href="{{ route('students.show', $student) }}">
                                                {{ $student->name }}
                                            </a>
                                        </td>
                                        <td>{{ $student->class->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-success">{{ ucfirst($student->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data siswa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Lihat Semua Siswa
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Tagihan Terlambat
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Jenis</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overduePayments as $bill)
                                    <tr>
                                        <td>
                                            <a href="{{ route('students.show', $bill->student) }}">
                                                {{ $bill->student->name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $bill->student->class->name ?? '-' }}</small>
                                        </td>
                                        <td>{{ $bill->paymentType->name }}</td>
                                        <td>
                                            <span class="badge badge-danger">
                                                {{ $bill->due_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($bill->final_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada tagihan terlambat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('bills.index') }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-eye"></i> Lihat Semua Tagihan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Ringkasan Keuangan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Tagihan Belum Lunas</span>
                                    <span class="info-box-number">
                                        Rp {{ number_format($stats['total_bills_amount'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pengguna</span>
                                    <span class="info-box-number">{{ $stats['total_users'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tahun Ajaran</span>
                                    <span class="info-box-number">2024/2025</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
