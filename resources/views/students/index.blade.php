@extends('adminlte::page')

@section('title', 'Data Siswa')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Data Siswa</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Siswa</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Siswa
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

                    <form method="GET" action="{{ route('students.index') }}" class="mb-3">
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
                            <div class="col-md-2">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">-- Status --</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                                    <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Pindah</option>
                                    <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Drop Out</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari NIS/Nama..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('students.index') }}" class="btn btn-default btn-sm">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    <tr>
                                        <td>{{ $students->firstItem() + $index }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nisn ?? '-' }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->class->name ?? '-' }}</td>
                                        <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>
                                            @if($student->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @elseif($student->status == 'graduated')
                                                <span class="badge badge-info">Lulus</span>
                                            @elseif($student->status == 'transferred')
                                                <span class="badge badge-warning">Pindah</span>
                                            @else
                                                <span class="badge badge-danger">Drop Out</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('students.show', $student) }}" class="btn btn-info btn-xs" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-xs" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
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
                                        <td colspan="8" class="text-center">Tidak ada data siswa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
