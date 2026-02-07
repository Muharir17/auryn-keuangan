@extends('adminlte::page')

@section('title', 'Tambah Siswa')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Siswa</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Siswa</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Siswa</h3>
                    <div class="card-tools">
                        <a href="{{ route('students.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Data Akademik</h5>
                                <hr>
                        <div class="form-group row">
                            <label for="nis" class="col-sm-4 col-form-label">NIS <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="nis" id="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" placeholder="Masukkan NIS" required>
                                @error('nis')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nisn" class="col-sm-4 col-form-label">NISN</label>
                            <div class="col-sm-8">
                                <input type="text" name="nisn" id="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn') }}" placeholder="Masukkan NISN (opsional)">
                                @error('nisn')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="class_id" class="col-sm-4 col-form-label">Kelas <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="class_id" id="class_id" class="form-control select2 @error('class_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->academic_year }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gender" class="col-sm-4 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status" class="col-sm-4 col-form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Lulus</option>
                                    <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>Pindah</option>
                                    <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Drop Out</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="enrollment_date" class="col-sm-4 col-form-label">Tanggal Masuk</label>
                            <div class="col-sm-8">
                                <input type="date" name="enrollment_date" id="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date') }}">
                                @error('enrollment_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                            </div>
                            <div class="col-md-6">
                                <h5>Data Pribadi</h5>
                                <hr>
                        <div class="form-group row">
                            <label for="birth_place" class="col-sm-4 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-8">
                                <input type="text" name="birth_place" id="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place') }}" placeholder="Masukkan tempat lahir">
                                @error('birth_place')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="birth_date" class="col-sm-4 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-8">
                                <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-4 col-form-label">Alamat</label>
                            <div class="col-sm-8">
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <h5 class="mt-4 mb-3 text-primary border-bottom pb-2">Data Orang Tua</h5>
                        <div class="form-group row">
                            <label for="parent_name" class="col-sm-4 col-form-label">Nama Orang Tua/Wali</label>
                            <div class="col-sm-8">
                                <input type="text" name="parent_name" id="parent_name" class="form-control @error('parent_name') is-invalid @enderror" value="{{ old('parent_name') }}" placeholder="Masukkan nama orang tua/wali">
                                @error('parent_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="parent_phone" class="col-sm-4 col-form-label">Telepon Orang Tua</label>
                            <div class="col-sm-8">
                                <input type="text" name="parent_phone" id="parent_phone" class="form-control @error('parent_phone') is-invalid @enderror" value="{{ old('parent_phone') }}" placeholder="Masukkan nomor telepon">
                                @error('parent_phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="parent_email" class="col-sm-4 col-form-label">Email Orang Tua</label>
                            <div class="col-sm-8">
                                <input type="email" name="parent_email" id="parent_email" class="form-control @error('parent_email') is-invalid @enderror" value="{{ old('parent_email') }}" placeholder="Masukkan email (opsional)">
                                @error('parent_email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notes" class="col-sm-4 col-form-label">Catatan</label>
                            <div class="col-sm-8">
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
