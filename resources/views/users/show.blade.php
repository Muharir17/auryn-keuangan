@extends('adminlte::page')

@section('title', 'Detail Wali Kelas - ' . $user->name)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detail Wali Kelas</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Wali Kelas</a></li>
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
                    <h3 class="card-title">Informasi Wali Kelas</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-xs">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-user-circle fa-5x text-muted"></i>
                    </div>
                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">Wali Kelas</p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Telepon</b> <span class="float-right">{{ $user->phone ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right">
                                @if($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Terdaftar</b> <span class="float-right">{{ $user->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelas yang Diwalikan</h3>
                </div>
                <div class="card-body">
                    @if($user->classes && $user->classes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kelas</th>
                                        <th>Level</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->classes as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $class->name }}</td>
                                            <td>Kelas {{ $class->level }}</td>
                                            <td>{{ $class->academic_year }}</td>
                                            <td>{{ $class->student_count ?? $class->students->count() }} siswa</td>
                                            <td>
                                                <a href="{{ route('classes.show', $class) }}" class="btn btn-info btn-xs">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Wali kelas ini belum ditugaskan ke kelas manapun.</p>
                            <a href="{{ route('classes.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Kelas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
