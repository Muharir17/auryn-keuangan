@extends('adminlte::page')

@section('title', 'Jenis Pembayaran')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Jenis Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Jenis Pembayaran</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Jenis Pembayaran</h3>
                    <div class="card-tools">
                        <a href="{{ route('payment-types.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Jenis
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jumlah Default</th>
                                    <th>Frekuensi</th>
                                    <th>Wajib</th>
                                    <th>Status</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentTypes as $index => $type)
                                    <tr>
                                        <td>{{ $paymentTypes->firstItem() + $index }}</td>
                                        <td><code>{{ $type->code }}</code></td>
                                        <td>{{ $type->name }}</td>
                                        <td>Rp {{ number_format($type->default_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($type->frequency == 'monthly')
                                                <span class="badge badge-info">Bulanan</span>
                                            @elseif($type->frequency == 'yearly')
                                                <span class="badge badge-primary">Tahunan</span>
                                            @elseif($type->frequency == 'once')
                                                <span class="badge badge-secondary">Sekali</span>
                                            @else
                                                <span class="badge badge-default">Kustom</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($type->is_mandatory)
                                                <span class="badge badge-danger">Wajib</span>
                                            @else
                                                <span class="badge badge-secondary">Opsional</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($type->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('payment-types.edit', $type) }}" class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('payment-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jenis pembayaran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data jenis pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $paymentTypes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
