@extends('adminlte::page')

@section('title', 'Detail Siswa - ' . $student->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Siswa</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Siswa</a></li>
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
                    <h3 class="card-title">Informasi Siswa</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $student->name }}</h3>
                    <p class="text-muted text-center">{{ $student->nis }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>NISN</b> <span class="float-right">{{ $student->nisn ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Kelas</b> <span class="float-right">{{ $student->class->name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Jenis Kelamin</b> <span class="float-right">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right">
                                @if($student->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($student->status == 'graduated')
                                    <span class="badge badge-info">Lulus</span>
                                @elseif($student->status == 'transferred')
                                    <span class="badge badge-warning">Pindah</span>
                                @else
                                    <span class="badge badge-danger">Drop Out</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Tempat/Tgl Lahir</b> 
                            <span class="float-right">
                                {{ $student->birth_place ?? '-' }}, {{ $student->birth_date?->format('d/m/Y') ?? '-' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Masuk</b> <span class="float-right">{{ $student->enrollment_date?->format('d/m/Y') ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Data Orang Tua</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nama</b> <span class="float-right">{{ $student->parent_name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Telepon</b> <span class="float-right">{{ $student->parent_phone ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right">{{ $student->parent_email ?? '-' }}</span>
                        </li>
                    </ul>
                    @if($student->address)
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Alamat</strong>
                        <p class="text-muted">{{ $student->address }}</p>
                    @endif
                    @if($student->notes)
                        <strong><i class="fas fa-sticky-note mr-1"></i> Catatan</strong>
                        <p class="text-muted">{{ $student->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#bills" data-toggle="tab">Tagihan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#payments" data-toggle="tab">Riwayat Pembayaran</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="bills">
                            <h4>Tagihan Siswa</h4>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No Tagihan</th>
                                            <th>Jenis</th>
                                            <th>Periode</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($student->bills as $bill)
                                            <tr>
                                                <td>{{ $bill->bill_number }}</td>
                                                <td>{{ $bill->paymentType->name ?? '-' }}</td>
                                                <td>{{ $bill->month ? sprintf('%02d', $bill->month) . '/' . $bill->year : $bill->year }}</td>
                                                <td>Rp {{ number_format($bill->final_amount, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($bill->status == 'paid')
                                                        <span class="badge badge-success">Lunas</span>
                                                    @elseif($bill->status == 'partial')
                                                        <span class="badge badge-warning">Sebagian</span>
                                                    @else
                                                        <span class="badge badge-danger">Belum Lunas</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center">Tidak ada tagihan</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <p><strong>Total Tunggakan:</strong> Rp {{ number_format($student->total_arrears ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="tab-pane" id="payments">
                            <h4>Riwayat Pembayaran</h4>
                            <p class="text-muted">Riwayat pembayaran akan ditampilkan di sini setelah modul pembayaran diimplementasikan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
