@extends('adminlte::page')

@section('title', 'Daftar Siswa - Kelas ' . $class->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Daftar Siswa Kelas {{ $class->name }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Kelas</a></li>
                <li class="breadcrumb-item active">{{ $class->name }}</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Siswa Kelas {{ $class->name }} - Tahun Ajaran {{ $class->academic_year }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.show', $class) }}" class="btn btn-default btn-sm mr-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Wali Kelas:</strong> {{ $class->homeroomTeacher->name ?? 'Belum ditentukan' }}</p>
                            <p><strong>Jumlah Siswa:</strong> {{ $students->total() }} siswa</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    <tr>
                                        <td>{{ $students->firstItem() + $index }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->nisn ?? '-' }}</td>
                                        <td>{{ $student->name }}</td>
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

                    <div class="d-flex justify-content-end">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
