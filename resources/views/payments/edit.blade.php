@extends('adminlte::page')

@section('title', 'Edit Pembayaran')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Edit Pembayaran</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
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
                <h3 class="card-title">Form Edit Pembayaran</h3>
                <div class="card-tools">
                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('payments.update', $payment) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Siswa</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control"
                                value="{{ $payment->student->name }} - {{ $payment->student->class->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Jenis Pembayaran</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{{ $payment->bill->paymentType->name }}"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amount" class="col-sm-4 col-form-label">Jumlah <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    value="{{ old('amount', $payment->amount) }}" placeholder="0" required>
                            </div>
                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="payment_date" class="col-sm-4 col-form-label">Tanggal Pembayaran <span
                                class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="date" name="payment_date" id="payment_date"
                                class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required>
                            @error('payment_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="payment_method" class="col-sm-4 col-form-label">Metode Pembayaran</label>
                        <div class="col-sm-8">
                            <select name="payment_method" id="payment_method"
                                class="form-control @error('payment_method') is-invalid @enderror">
                                <option value="">-- Pilih Metode --</option>
                                <option value="transfer" {{ old('payment_method', $payment->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="e-wallet" {{ old('payment_method', $payment->payment_method) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="cheque" {{ old('payment_method', $payment->payment_method) == 'cheque' ? 'selected' : '' }}>Cek</option>
                                <option value="other" {{ old('payment_method', $payment->payment_method) == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('payment_method')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="payment_slip" class="col-sm-4 col-form-label">Bukti Pembayaran Baru</label>
                        <div class="col-sm-8">
                            <div class="custom-file">
                                <input type="file" name="payment_slip" id="payment_slip"
                                    class="custom-file-input @error('payment_slip') is-invalid @enderror"
                                    accept="image/*,.pdf">
                                <label class="custom-file-label" for="payment_slip">Pilih file baru
                                    (opsional)...</label>
                            </div>
                            <small class="form-text text-muted">
                                Format: JPG, PNG, PDF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah bukti.
                            </small>
                            @error('payment_slip')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="notes" class="col-sm-4 col-form-label">Catatan</label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                rows="3"
                                placeholder="Catatan tambahan (opsional)">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        @if($payment->paymentSlips->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bukti Pembayaran Saat Ini</h3>
                </div>
                <div class="card-body">
                    @foreach($payment->paymentSlips as $slip)
                        <div class="mb-3">
                            <h6>{{ $slip->original_filename }}</h6>
                            @if(in_array($slip->file_type, ['image/jpeg', 'image/png', 'image/jpg']))
                                <img src="{{ asset('storage/' . $slip->file_path) }}" class="img-fluid" alt="Bukti Pembayaran">
                            @elseif($slip->file_type == 'application/pdf')
                                <a href="{{ asset('storage/' . $slip->file_path) }}" target="_blank"
                                    class="btn btn-primary btn-block">
                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                </a>
                            @endif
                            <small class="text-muted d-block mt-2">
                                Diunggah: {{ $slip->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            // Update file label
            $('#payment_slip').on('change', function () {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Pilih file baru (opsional)...');
            });
        });
    </script>
@endpush