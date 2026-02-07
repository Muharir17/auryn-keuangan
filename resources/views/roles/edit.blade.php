@extends('adminlte::page')

@section('title', 'Edit Role')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Role</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $role->name) }}" 
                                   placeholder="Contoh: manager" required
                                   {{ in_array($role->name, ['admin', 'teacher', 'finance', 'principal', 'foundation']) ? 'readonly' : '' }}>
                            <small class="form-text text-muted">Gunakan huruf kecil, tanpa spasi (gunakan - atau _)</small>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $role->title) }}" 
                                   placeholder="Contoh: Manager" required>
                            <small class="form-text text-muted">Nama yang akan ditampilkan</small>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($role->name !== 'admin')
                            <div class="form-group">
                                <label>Abilities</label>
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="select-all">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all">
                                        <i class="fas fa-square"></i> Deselect All
                                    </button>
                                </div>
                                <div class="row">
                                    @foreach($abilities as $ability)
                                        <div class="col-md-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="ability_{{ $ability->id }}" 
                                                       name="abilities[]" 
                                                       value="{{ $ability->id }}"
                                                       {{ in_array($ability->id, old('abilities', $roleAbilities)) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ability_{{ $ability->id }}">
                                                    <strong>{{ $ability->name }}</strong>
                                                    @if($ability->title)
                                                        <br><small class="text-muted">{{ $ability->title }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('abilities')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Role <strong>admin</strong> memiliki semua abilities secara otomatis.
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#select-all').click(function() {
            $('input[name="abilities[]"]').prop('checked', true);
        });
        
        $('#deselect-all').click(function() {
            $('input[name="abilities[]"]').prop('checked', false);
        });
    });
</script>
@stop
