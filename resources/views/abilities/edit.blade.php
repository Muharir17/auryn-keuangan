@extends('adminlte::page')

@section('title', 'Edit Ability')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Edit Ability</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('abilities.index') }}">Abilities</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <form action="{{ route('abilities.update', $ability) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $ability->name) }}" placeholder="Contoh: manage-products"
                            required>
                        <small class="form-text text-muted">Gunakan huruf kecil dengan tanda hubung (-)</small>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $ability->title) }}" placeholder="Contoh: Kelola Produk"
                            required>
                        <small class="form-text text-muted">Deskripsi yang mudah dibaca</small>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="entity_type">Entity Type (Optional)</label>
                        <input type="text" class="form-control @error('entity_type') is-invalid @enderror"
                            id="entity_type" name="entity_type" value="{{ old('entity_type', $ability->entity_type) }}"
                            placeholder="Contoh: App\Models\Product">
                        <small class="form-text text-muted">Model class jika ability ini terkait dengan model
                            tertentu</small>
                        @error('entity_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Info:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Ability ini digunakan oleh <strong>{{ $ability->roles()->count() }}</strong> role(s)
                            </li>
                            <li>Ability ini digunakan oleh <strong>{{ $ability->users()->count() }}</strong> user(s)
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('abilities.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop