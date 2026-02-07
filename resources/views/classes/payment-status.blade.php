@extends('adminlte::page')

@section('title', 'Status Pembayaran - Kelas ' . $class->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Status Pembayaran Kelas {{ $class->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Kelas</a></li>
                <li class="breadcrumb-item active">Status Pembayaran</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Pembayaran Tahun {{ date('Y') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.show', $class) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Kelas:</strong> {{ $class->name }}</p>
                            <p><strong>Wali Kelas:</strong> {{ $class->homeroomTeacher->name ?? 'Belum ditentukan' }}</p>
                            <p><strong>Jumlah Siswa:</strong> {{ $students->count() }} siswa</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Jumlah Tagihan</th>
                                    <th>Lunas</th>
                                    <th>Belum Lunas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    @php
                                        $totalBills = $student->bills->count();
                                        $paidBills = $student->bills->where('status', 'paid')->count();
                                        $unpaidBills = $totalBills - $paidBills;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>
                                            <a href="{{ route('students.show', $student) }}">
                                                {{ $student->name }}
                                            </a>
                                        </td>
                                        <td>{{ $totalBills }} tagihan</td>
                                        <td>
                                            @if($paidBills > 0)
                                                <span class="badge badge-success">{{ $paidBills }} lunas</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($unpaidBills > 0)
                                                <span class="badge badge-danger">{{ $unpaidBills }} belum</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($totalBills == 0)
                                                <span class="badge badge-secondary">Belum ada tagihan</span>
                                            @elseif($paidBills == $totalBills)
                                                <span class="badge badge-success">Lunas Semua</span>
                                            @elseif($paidBills > 0)
                                                <span class="badge badge-warning">Sebagian</span>
                                            @else
                                                <span class="badge badge-danger">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada siswa di kelas ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
