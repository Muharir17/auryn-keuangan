@extends('adminlte::page')

@section('title', 'Detail Role')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Detail Role</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Role</h3>
                <div class="card-tools">
                    <a href="{{ route('roles.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @can('manage-roles')
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 150px;">Nama Role</th>
                        <td>
                            <span class="badge badge-info">{{ $role->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $role->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah User</th>
                        <td>
                            <span class="badge badge-primary">{{ $role->users->count() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $role->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui</th>
                        <td>{{ $role->updated_at ? $role->updated_at->format('d M Y H:i:s') : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Abilities</h3>
            </div>
            <div class="card-body">
                @if($role->name === 'admin')
                    <div class="alert alert-success">
                        <i class="fas fa-crown"></i> Role ini memiliki <strong>semua abilities</strong> (full access)
                    </div>
                @else
                    @php
                        $abilities = $role->abilities ?? collect();
                    @endphp
                    @if($abilities->count() > 0)
                        <div class="row">
                            @foreach($abilities as $ability)
                                <div class="col-md-6 mb-2">
                                    <span class="badge badge-secondary">{{ $ability->name }}</span>
                                    @if($ability->title)
                                        <br><small class="text-muted">{{ $ability->title }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Role ini tidak memiliki abilities.</p>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User dengan Role Ini</h3>
            </div>
            <div class="card-body">
                @if($role->users->isNotEmpty())
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($role->users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Tidak ada user dengan role ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@stop