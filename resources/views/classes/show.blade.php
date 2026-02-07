@extends('adminlte::page')

@section('title', 'Detail Kelas - ' . $class->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Kelas</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Kelas</a></li>
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
                    <h3 class="card-title">Informasi Kelas</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">{{ $class->name }}</h3>
                    <p class="text-muted text-center">Tahun Ajaran {{ $class->academic_year }}</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Level</b> <span class="float-right">Kelas {{ $class->level }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Wali Kelas</b> <span class="float-right">{{ $class->homeroomTeacher->name ?? 'Belum ditentukan' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Jumlah Siswa</b> <span class="float-right">{{ $class->students->count() }} siswa</span>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right">
                                @if($class->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                    <a href="{{ route('classes.students', $class) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-users"></i> Lihat Daftar Siswa
                    </a>
                    <a href="{{ route('classes.payment-status', $class) }}" class="btn btn-success btn-block mt-2">
                        <i class="fas fa-money-bill-wave"></i> Status Pembayaran
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
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
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>
                                            @if($student->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($student->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('students.show', $student) }}" class="btn btn-info btn-xs">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada siswa di kelas ini</td>
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
