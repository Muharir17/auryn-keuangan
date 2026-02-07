@extends('adminlte::page')

@section('title', 'Dashboard Eksekutif')

@section('content_header')
<h1>Dashboard Eksekutif</h1>
@stop

@section('content')
{{-- Row 1: Key Metrics --}}
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_students'] }}</h3>
                <p>Total Siswa Aktif</p>
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
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp {{ number_format($stats['total_income'] / 1000000, 1) }}M</h3>
                <p>Total Pemasukan (Tahun Ini)</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="{{ route('reports.financial') }}" class="small-box-footer">
                Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ 100 - number_format($bosStats['percentage'], 1) }}<sup style="font-size: 20px">%</sup></h3>
                <p>Sisa Budget BOS {{ $bosStats['budget_year'] }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-wallet"></i>
            </div>
            <a href="{{ route('bos.budgets') }}" class="small-box-footer">
                Lihat Budget <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Row 2: Action Required & Analytics --}}
<div class="row">
    {{-- Pending Proposals --}}
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-check"></i>
                    Proposal Menunggu Persetujuan
                </h3>
                <div class="card-tools">
                    <span class="badge badge-warning">{{ $pendingProposals->count() }} Pending</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Judul Proposal</th>
                                <th>Diajukan Oleh</th>
                                <th class="text-right">Nilai Anggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingProposals as $proposal)
                                <tr>
                                    <td>{{ $proposal->created_at->format('d M Y') }}</td>
                                    <td>
                                        <strong>{{ Str::limit($proposal->title, 40) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($proposal->description, 50) }}</small>
                                    </td>
                                    <td>{{ $proposal->submitter->name }}</td>
                                    <td class="text-right">Rp {{ number_format($proposal->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('proposals.approval') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-search"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                        <p class="mb-0">Tidak ada proposal yang menunggu persetujuan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('proposals.approval') }}" class="uppercase">Lihat Semua Proposal</a>
            </div>
        </div>
    </div>

    {{-- Quick Monthly Income Table (Simple Chart replacement) --}}
    <div class="col-md-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-money-bill-wave"></i>
                    Pemasukan Bulanan
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyIncome as $data)
                            <tr>
                                <td>{{ date('F', mktime(0, 0, 0, $data->month, 1)) }}</td>
                                <td class="text-right">Rp {{ number_format($data->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Belum ada data pemasukan tahun ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td>TOTAL</td>
                            <td class="text-right">Rp {{ number_format($monthlyIncome->sum('total'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Budget Status Box --}}
        <div class="info-box mb-3 bg-info">
            <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Download Laporan</span>
                <span class="info-box-number">Export PDF</span>
            </div>
            <div class="d-flex flex-column justify-content-center px-2">
                <a href="{{ route('reports.financial.pdf') }}" class="btn btn-xs btn-outline-light mb-1"
                    target="_blank">Keuangan</a>
                <a href="{{ route('bos.budgets.pdf') }}" class="btn btn-xs btn-outline-light" target="_blank">BOS</a>
            </div>
        </div>
    </div>
</div>
@stop