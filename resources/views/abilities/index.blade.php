@extends('adminlte::page')

@section('title', 'Manajemen Abilities')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Manajemen Abilities</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Abilities</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Abilities</h3>
                <div class="card-tools">
                    @can('manage-permissions')
                        <a href="{{ route('abilities.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Ability
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Entity Type</th>
                            <th>Roles</th>
                            <th>Users</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($abilities as $index => $ability)
                            <tr>
                                <td>{{ $abilities->firstItem() + $index }}</td>
                                <td><code>{{ $ability->name }}</code></td>
                                <td>{{ $ability->title ?? '-' }}</td>
                                <td>
                                    @if($ability->entity_type)
                                        <span class="badge badge-info">{{ class_basename($ability->entity_type) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $ability->roles()->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-success">{{ $ability->users()->count() }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('abilities.show', $ability) }}" class="btn btn-info btn-sm"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('manage-permissions')
                                            <a href="{{ route('abilities.edit', $ability) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('abilities.destroy', $ability) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ability ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data ability</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $abilities->links() }}
            </div>
        </div>
    </div>
</div>
@stop