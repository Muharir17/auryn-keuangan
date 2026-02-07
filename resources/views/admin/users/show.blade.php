@extends('adminlte::page')

@section('title', 'Detail User')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Detail User</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
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
                <h3 class="card-title">Informasi User</h3>
                <div class="card-tools">
                    @can('manage-users')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Nama</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Terdaftar</th>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Login</th>
                        <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Roles</h3>
            </div>
            <div class="card-body">
                @php
                    $roles = $user->roles ?? collect();
                @endphp
                @if($roles->count() > 0)
                    <ul class="list-group">
                        @foreach($roles as $role)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge badge-info">{{ $role->name }}</span>
                                    @if($role->title)
                                        <br><small class="text-muted">{{ $role->title }}</small>
                                    @endif
                                </div>
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">User ini belum memiliki role.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Abilities (Via Roles)</h3>
            </div>
            <div class="card-body">
                @php
                    $abilities = $user->getAbilities() ?? collect();
                @endphp
                @if($abilities->count() > 0)
                    <div class="row">
                        @foreach($abilities as $ability)
                            <div class="col-md-6 mb-2">
                                <span class="badge badge-secondary">{{ $ability->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">User ini belum memiliki abilities.</p>
                @endif
            </div>
        </div>

        @can('manage-users')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Direct Abilities</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.assign-ability', $user) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Tambah Ability Langsung</label>
                            <select name="ability" class="form-control">
                                <option value="">-- Pilih Ability --</option>
                                @foreach($allAbilities as $ability)
                                    <option value="{{ $ability->name }}">{{ $ability->name }} - {{ $ability->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </form>

                    <hr>

                    @php
                        $directAbilities = $user->abilities ?? collect();
                    @endphp
                    @if($directAbilities->count() > 0)
                        <ul class="list-group">
                            @foreach($directAbilities as $ability)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="badge badge-warning">{{ $ability->name }}</span>
                                    <form action="{{ route('admin.users.remove-ability', $user) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="ability" value="{{ $ability->name }}">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus ability ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Tidak ada direct abilities.</p>
                    @endif
                </div>
            </div>
        @endcan
    </div>
</div>
@stop