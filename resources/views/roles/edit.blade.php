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
                <div class="card-header">
                    <h3 class="card-title">Form Edit Role - {{ $role->display_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label">Nama Role <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" placeholder="Contoh: content-writer" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Nama unik untuk role (tanpa spasi)</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="display_name" class="col-sm-4 col-form-label">Display Name <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" name="display_name" id="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name', $role->display_name) }}" placeholder="Contoh: Content Writer" required>
                                @error('display_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Nama yang akan ditampilkan di UI</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-4 col-form-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Deskripsi role (opsional)">{{ old('description', $role->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Permissions</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>User Management</h6>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-users" class="form-check-input" id="perm-manage-users" {{ old('permissions', $role->permissions)->contains('manage-users') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-users">Kelola Users</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-roles" class="form-check-input" id="perm-manage-roles" {{ old('permissions', $role->permissions)->contains('manage-roles') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-roles">Kelola Roles</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-permissions" class="form-check-input" id="perm-manage-permissions" {{ old('permissions', $role->permissions)->contains('manage-permissions') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-permissions">Kelola Permissions</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-wali-kelas" class="form-check-input" id="perm-manage-wali-kelas" {{ old('permissions', $role->permissions)->contains('manage-wali-kelas') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-wali-kelas">Kelola Wali Kelas</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Data Management</h6>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-classes" class="form-check-input" id="perm-manage-classes" {{ old('permissions', $role->permissions)->contains('manage-classes') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-classes">Kelola Kelas</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-students" class="form-check-input" id="perm-manage-students" {{ old('permissions', $role->permissions)->contains('manage-students') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-students">Kelola Siswa</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6>Financial Management</h6>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-payment-types" class="form-check-input" id="perm-manage-payment-types" {{ old('permissions', $role->permissions)->contains('manage-payment-types') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-payment-types">Kelola Jenis Pembayaran</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-bills" class="form-check-input" id="perm-manage-bills" {{ old('permissions', $role->permissions)->contains('manage-bills') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-bills">Kelola Tagihan</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="view-payments" class="form-check-input" id="perm-view-payments" {{ old('permissions', $role->permissions)->contains('view-payments') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-view-payments">Lihat Pembayaran</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="validate-payments" class="form-check-input" id="perm-validate-payments" {{ old('permissions', $role->permissions)->contains('validate-payments') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-validate-payments">Validasi Pembayaran</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Reports</h6>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="view-reports" class="form-check-input" id="perm-view-reports" {{ old('permissions', $role->permissions)->contains('view-reports') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-view-reports">Lihat Laporan</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="export-reports" class="form-check-input" id="perm-export-reports" {{ old('permissions', $role->permissions)->contains('export-reports') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-export-reports">Export Laporan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h6>System</h6>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="view-logs" class="form-check-input" id="perm-view-logs" {{ old('permissions', $role->permissions)->contains('view-logs') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-view-logs">Lihat Log Sistem</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" value="manage-settings" class="form-check-input" id="perm-manage-settings" {{ old('permissions', $role->permissions)->contains('manage-settings') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-manage-settings">Kelola Pengaturan</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
