@extends('adminlte::page')

@section('title', 'Tambah Pembayaran')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Pembayaran</h3>
                    <div class="card-tools">
                        <a href="{{ route('payments.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="student_id" class="col-sm-4 col-form-label">Siswa <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="student_id" id="student_id" class="form-control select2 @error('student_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} - {{ $student->class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bill_id" class="col-sm-4 col-form-label">Tagihan <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="bill_id" id="bill_id" class="form-control select2 @error('bill_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Tagihan --</option>
                                </select>
                                @error('bill_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-sm-4 col-form-label">Jumlah <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="0" required>
                                </div>
                                @error('amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="payment_date" class="col-sm-4 col-form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="payment_method" class="col-sm-4 col-form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="e-wallet" {{ old('payment_method') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                    <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('payment_method')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="payment_slips" class="col-sm-4 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-8">
                                <div class="custom-file">
                                    <input type="file" name="payment_slips[]" id="payment_slips" class="custom-file-input @error('payment_slips') is-invalid @enderror" accept="image/*,.pdf" multiple>
                                    <label class="custom-file-label" for="payment_slips">Pilih file bukti pembayaran...</label>
                                </div>
                                <small class="form-text text-muted">
                                    Format: JPG, PNG, PDF. Maksimal 5MB per file. Bisa upload multiple file.
                                </small>
                                @error('payment_slips')
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
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('payments.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

    // Load bills when student is selected
    $('#student_id').on('change', function() {
        var studentId = $(this).val();
        $('#bill_id').empty().append('<option value="">-- Pilih Tagihan --</option>');
        
        if(studentId) {
            $.get('/api/students/' + studentId + '/bills', function(data) {
                $.each(data, function(key, bill) {
                    $('#bill_id').append('<option value="' + bill.id + '">' + bill.payment_type.name + ' - ' + bill.due_date + ' (Rp ' + bill.amount_formatted + ')</option>');
                });
            });
        }
    });

    // Update file label
    $('#payment_slips').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file bukti pembayaran...');
    });
});
</script>
@endpush
