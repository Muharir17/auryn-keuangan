@extends('adminlte::page')

@section('title', 'Data Tunggakan')

@section('content_header')
    <h1><i class="fas fa-exclamation-circle"></i> Data Tunggakan</h1>
@stop

@section('content')
    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-md-4">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Tunggakan</span>
                    <span class="info-box-number">Rp {{ number_format($totalArrears, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tunggakan Terlambat</span>
                    <span class="info-box-number">Rp {{ number_format($totalOverdue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Siswa dengan Tunggakan</span>
                    <span class="info-box-number">{{ $studentsWithArrears }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tunggakan</h3>
                </div>
                <div class="card-body">
                    {{-- Filters --}}
                    <form method="GET" action="{{ route('arrears.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="class_id" class="form-control form-control-sm">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="student_id" class="form-control form-control-sm">
                                    <option value="">-- Semua Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="overdue_only" class="form-control form-control-sm">
                                    <option value="">Semua</option>
                                    <option value="1" {{ request('overdue_only') == '1' ? 'selected' : '' }}>Hanya Terlambat</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('arrears.index') }}" class="btn btn-default btn-sm">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No Tagihan</th>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Periode</th>
                                    <th>Jumlah</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($arrears as $bill)
                                    <tr class="{{ $bill->is_overdue ? 'table-danger' : '' }}">
                                        <td>{{ $bill->bill_number }}</td>
                                        <td>{{ $bill->student->name }}</td>
                                        <td>{{ $bill->student->class->name }}</td>
                                        <td>{{ $bill->paymentType->name }}</td>
                                        <td>
                                            @if($bill->month)
                                                {{ sprintf('%02d', $bill->month) }}/{{ $bill->year }}
                                            @else
                                                {{ $bill->year }}
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($bill->final_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($bill->is_overdue)
                                                <span class="badge badge-danger">{{ $bill->due_date->format('d/m/Y') }}</span>
                                            @else
                                                {{ $bill->due_date->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($bill->status == 'partial')
                                                <span class="badge badge-warning">Sebagian</span>
                                            @else
                                                <span class="badge badge-danger">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-success">
                                            <i class="fas fa-check-circle"></i> Tidak ada tunggakan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $arrears->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
