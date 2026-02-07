@extends('adminlte::page')

@section('title', 'Data Tagihan')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Data Tagihan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Tagihan</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tagihan</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.bills.pdf', request()->all()) }}" class="btn btn-danger btn-sm mr-2" target="_blank">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                        <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#generateModal">
                            <i class="fas fa-magic"></i> Generate Bulanan
                        </button>
                        <a href="{{ route('bills.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Tagihan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="GET" action="{{ route('bills.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">-- Status --</option>
                                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="payment_type_id" class="form-control form-control-sm">
                                    <option value="">-- Jenis Pembayaran --</option>
                                    @foreach($paymentTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('payment_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="month" class="form-control form-control-sm">
                                    <option value="">-- Bulan --</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="year" class="form-control form-control-sm" placeholder="Tahun" value="{{ request('year') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('bills.index') }}" class="btn btn-default btn-sm">
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
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bills as $bill)
                                    <tr>
                                        <td>{{ $bill->bill_number }}</td>
                                        <td>{{ $bill->student->name ?? '-' }}</td>
                                        <td>{{ $bill->student->class->name ?? '-' }}</td>
                                        <td>{{ $bill->paymentType->name ?? '-' }}</td>
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
                                            @if($bill->status == 'paid')
                                                <span class="badge badge-success">Lunas</span>
                                            @elseif($bill->status == 'partial')
                                                <span class="badge badge-warning">Sebagian</span>
                                            @else
                                                <span class="badge badge-danger">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('bills.show', $bill) }}" class="btn btn-info btn-xs" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('bills.edit', $bill) }}" class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data tagihan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $bills->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Monthly Modal -->
    <div class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('bills.generate-monthly') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateModalLabel">Generate Tagihan Bulanan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="gen_month">Bulan <span class="text-danger">*</span></label>
                            <select name="month" id="gen_month" class="form-control" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gen_year">Tahun <span class="text-danger">*</span></label>
                            <input type="number" name="year" id="gen_year" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="gen_payment_type">Jenis Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_type_id" id="gen_payment_type" class="form-control" required>
                                @foreach($paymentTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gen_class">Kelas (Opsional)</label>
                            <select name="class_id" id="gen_class" class="form-control">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
