@extends('adminlte::page')

@section('title', 'Data Kelas')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Data Kelas</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Kelas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kelas</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Kelas
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
                                    <th>Nama Kelas</th>
                                    <th>Level</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Wali Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classes as $index => $class)
                                    <tr>
                                        <td>{{ $classes->firstItem() + $index }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td>Kelas {{ $class->level }}</td>
                                        <td>{{ $class->academic_year }}</td>
                                        <td>{{ $class->homeroomTeacher->name ?? 'Belum ditentukan' }}</td>
                                        <td>
                                            <a href="{{ route('classes.students', $class) }}">
                                                {{ $class->students_count ?? $class->student_count }} siswa
                                            </a>
                                        </td>
                                        <td>
                                            @if($class->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('classes.show', $class) }}" class="btn btn-info btn-xs" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('classes.payment-status', $class) }}" class="btn btn-success btn-xs" title="Status Pembayaran">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </a>
                                            <form action="{{ route('classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
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
                                        <td colspan="8" class="text-center">Tidak ada data kelas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $classes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
