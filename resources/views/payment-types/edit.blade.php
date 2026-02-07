@extends('adminlte::page')

@section('title', 'Edit Jenis Pembayaran')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Jenis Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payment-types.index') }}">Jenis Pembayaran</a></li>
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
                    <h3 class="card-title">Form Edit Jenis Pembayaran - {{ $paymentType->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('payment-types.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('payment-types.update', $paymentType) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="code" class="col-sm-4 col-form-label">Kode <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $paymentType->code) }}" required>
                                        @error('code')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">Kode unik untuk identifikasi</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="name" class="col-sm-4 col-form-label">Nama <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $paymentType->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="2" placeholder="Deskripsi jenis pembayaran (opsional)">{{ old('description', $paymentType->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="default_amount" class="col-sm-4 col-form-label">Jumlah Default (Rp) <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="number" name="default_amount" id="default_amount" class="form-control @error('default_amount') is-invalid @enderror" value="{{ old('default_amount', $paymentType->default_amount) }}" min="0" required>
                                        @error('default_amount')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="frequency" class="col-sm-4 col-form-label">Frekuensi <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="frequency" id="frequency" class="form-control @error('frequency') is-invalid @enderror" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="monthly" {{ old('frequency', $paymentType->frequency) == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                            <option value="yearly" {{ old('frequency', $paymentType->frequency) == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                            <option value="once" {{ old('frequency', $paymentType->frequency) == 'once' ? 'selected' : '' }}>Sekali</option>
                                            <option value="custom" {{ old('frequency', $paymentType->frequency) == 'custom' ? 'selected' : '' }}>Kustom</option>
                                        </select>
                                        @error('frequency')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="sort_order" class="col-sm-4 col-form-label">Urutan</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $paymentType->sort_order) }}" min="0">
                                        @error('sort_order')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">Urutan tampilan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_mandatory" id="is_mandatory" class="custom-control-input" value="1" {{ old('is_mandatory', $paymentType->is_mandatory) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_mandatory">
                                                <strong>Pembayaran Wajib</strong>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Centang jika semua siswa wajib membayar</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" {{ old('is_active', $paymentType->is_active) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">
                                                <strong>Aktif</strong>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Centang untuk mengaktifkan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('payment-types.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
