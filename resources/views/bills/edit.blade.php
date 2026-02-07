@extends('adminlte::page')

@section('title', 'Edit Tagihan')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Edit Tagihan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bills.index') }}">Tagihan</a></li>
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
                    <h3 class="card-title">Form Edit Tagihan - {{ $bill->bill_number }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('bills.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('bills.update', $bill) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Siswa</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="{{ $bill->student->name ?? '-' }} ({{ $bill->student->nis ?? '-' }})" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="payment_type_id" class="col-sm-4 col-form-label">Jenis Pembayaran <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select name="payment_type_id" id="payment_type_id" class="form-control @error('payment_type_id') is-invalid @enderror" required>
                                            @foreach($paymentTypes as $type)
                                                <option value="{{ $type->id }}" data-amount="{{ $type->default_amount }}" {{ old('payment_type_id', $bill->payment_type_id) == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }} - Rp {{ number_format($type->default_amount, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('payment_type_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="month" class="col-sm-4 col-form-label">Bulan</label>
                                    <div class="col-sm-8">
                                        <select name="month" id="month" class="form-control @error('month') is-invalid @enderror">
                                            <option value="">-- Tidak ada --</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('month', $bill->month) == $i ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('month')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="year" class="col-sm-4 col-form-label">Tahun <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $bill->year) }}" required>
                                        @error('year')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="due_date" class="col-sm-4 col-form-label">Jatuh Tempo <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}" required>
                                        @error('due_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="amount" class="col-sm-4 col-form-label">Jumlah <span class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $bill->amount) }}" min="0" required>
                                        @error('amount')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">Diskon</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $bill->discount) }}" min="0">
                                        @error('discount')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Total</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="final_amount_display" class="form-control font-weight-bold text-success" value="Rp {{ number_format($bill->final_amount, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="notes" class="col-sm-2 col-form-label">Catatan</label>
                            <div class="col-sm-10">
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('notes', $bill->notes) }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <div class="row align-items-center">
                                <div class="col-sm-3">
                                    <strong>Status Saat Ini:</strong>
                                </div>
                                <div class="col-sm-9">
                                    @if($bill->status == 'paid')
                                        <span class="badge badge-success badge-lg">Lunas</span>
                                    @elseif($bill->status == 'partial')
                                        <span class="badge badge-warning badge-lg">Sebagian</span>
                                    @else
                                        <span class="badge badge-danger badge-lg">Belum Lunas</span>
                                    @endif
                                    <br><small class="text-muted">Status akan otomatis diupdate berdasarkan pembayaran yang diterima.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <a href="{{ route('bills.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(function() {
        function calculateFinal() {
            var amount = parseFloat($('#amount').val()) || 0;
            var discount = parseFloat($('#discount').val()) || 0;
            var final = amount - discount;
            $('#final_amount_display').val('Rp ' + final.toLocaleString('id-ID'));
        }

        $('#amount, #discount').on('input', calculateFinal);
    });
</script>
@stop
