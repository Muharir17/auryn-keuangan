@extends('adminlte::page')

@section('title', 'Tambah Kelas')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Kelas</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">Kelas</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Kelas</h3>
                    <div class="card-tools">
                        <a href="{{ route('classes.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('classes.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: 7A, 8B, 9C" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="level" class="col-sm-4 col-form-label">Level/Tingkat <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="level" id="level" class="form-control @error('level') is-invalid @enderror" required>
                                    <option value="">-- Pilih Level --</option>
                                    <option value="7" {{ old('level') == '7' ? 'selected' : '' }}>Kelas 7</option>
                                    <option value="8" {{ old('level') == '8' ? 'selected' : '' }}>Kelas 8</option>
                                    <option value="9" {{ old('level') == '9' ? 'selected' : '' }}>Kelas 9</option>
                                </select>
                                @error('level')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="academic_year" class="col-sm-4 col-form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" value="{{ old('academic_year', date('Y') . '/' . (date('Y') + 1)) }}" placeholder="Contoh: 2024/2025" required>
                                @error('academic_year')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="homeroom_teacher_id" class="col-sm-4 col-form-label">Wali Kelas</label>
                            <div class="col-sm-8">
                                <select name="homeroom_teacher_id" id="homeroom_teacher_id" class="form-control select2 @error('homeroom_teacher_id') is-invalid @enderror">
                                    <option value="">-- Belum ditentukan --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('homeroom_teacher_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Kelas Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('classes.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
