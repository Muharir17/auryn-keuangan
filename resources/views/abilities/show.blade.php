@extends('adminlte::page')

@section('title', 'Detail Ability')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Detail Ability</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('abilities.index') }}">Abilities</a></li>
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
                <h3 class="card-title">Informasi Ability</h3>
                <div class="card-tools">
                    @can('manage-permissions')
                        <a href="{{ route('abilities.edit', $ability) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                    <a href="{{ route('abilities.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Name</th>
                        <td><code>{{ $ability->name }}</code></td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $ability->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Entity Type</th>
                        <td>
                            @if($ability->entity_type)
                                <span class="badge badge-info">{{ class_basename($ability->entity_type) }}</span>
                                <br><small class="text-muted">{{ $ability->entity_type }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $ability->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Diupdate</th>
                        <td>{{ $ability->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Roles dengan Ability Ini</h3>
            </div>
            <div class="card-body">
                @php
                    $roles = $ability->roles ?? collect();
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
                    <p class="text-muted">Belum ada role yang menggunakan ability ini.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users dengan Ability Ini (Direct)</h3>
            </div>
            <div class="card-body">
                @php
                    $users = $ability->users ?? collect();
                @endphp
                @if($users->count() > 0)
                    <ul class="list-group">
                        @foreach($users as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <br><small class="text-muted">{{ $user->email }}</small>
                                </div>
                                @if($user->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Belum ada user yang memiliki ability ini secara langsung.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@stop