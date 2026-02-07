@extends('adminlte::page')

@section('title', 'Manajemen Roles')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Manajemen Roles</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Roles</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Role
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Role</th>
                                <th>Display Name</th>
                                <th>Deskripsi</th>
                                <th>Jumlah User</th>
                                <th>Permissions</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $role)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $role->name }}</span>
                                    </td>
                                    <td>{{ $role->display_name }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $role->users_count }}</span>
                                    </td>
                                    <td>
                                        @if($role->permissions)
                                            @foreach($role->permissions as $permission)
                                                <span class="badge badge-secondary mr-1">{{ $permission }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('roles.show', $role) }}" class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(!in_array($role->name, ['admin', 'teacher', 'finance', 'principal', 'foundation']))
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus role ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data role</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
@stop
