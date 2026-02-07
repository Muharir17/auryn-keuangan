@extends('adminlte::page')

@section('title', 'Detail Tunggakan - ' . $student->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Tunggakan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Siswa</a></li>
                <li class="breadcrumb-item active">Detail Tunggakan</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Tunggakan - {{ $student->name }}</h3>
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
                            <p><strong>Total Tunggakan:</strong> <span class="badge badge-danger">Rp {{ number_format($student->total_arrears ?? 0, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    @if($student->arrears && $student->arrears->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Tagihan</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah Tunggakan</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->arrears as $index => $arrear)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $arrear->bill->bill_number ?? '-' }}</td>
                                            <td>{{ $arrear->bill->paymentType->name ?? '-' }}</td>
                                            <td>Rp {{ number_format($arrear->arrears_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if($arrear->status == 'paid')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif($arrear->status == 'partial')
                                                    <span class="badge badge-warning">Sebagian</span>
                                                @else
                                                    <span class="badge badge-danger">Belum Lunas</span>
                                                @endif
                                            </td>
                                            <td>{{ $arrear->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Tidak ada tunggakan.</p>
                            <p class="text-muted">Siswa ini sudah melunasi semua tagihan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
