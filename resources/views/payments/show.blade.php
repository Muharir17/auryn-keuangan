@extends('adminlte::page')

@section('title', 'Detail Pembayaran')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Detail Pembayaran</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pembayaran</h3>
                <div class="card-tools">
                    <a href="{{ route('payments.index') }}" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tanggal Pembayaran</th>
                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Siswa</th>
                        <td>
                            <a href="{{ route('students.show', $payment->student) }}">
                                {{ $payment->student->name }}
                            </a>
                            <br>
                            <small class="text-muted">{{ $payment->student->nis }} -
                                {{ $payment->student->class->name }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Pembayaran</th>
                        <td>{{ $payment->bill->paymentType->name }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($payment->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($payment->status == 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($payment->status == 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @if($payment->receipt_number)
                        <tr>
                            <th>Nomor Kwitansi</th>
                            <td>{{ $payment->receipt_number }}</td>
                        </tr>
                    @endif
                    @if($payment->validated_at)
                        <tr>
                            <th>Divalidasi Oleh</th>
                            <td>
                                {{ $payment->validator->name }}
                                <br>
                                <small class="text-muted">{{ $payment->validated_at->format('d M Y H:i') }}</small>
                            </td>
                        </tr>
                    @endif
                    @if($payment->rejection_reason)
                        <tr>
                            <th>Alasan Penolakan</th>
                            <td><span class="text-danger">{{ $payment->rejection_reason }}</span></td>
                        </tr>
                    @endif
                    @if($payment->notes)
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $payment->notes }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Diunggah Oleh</th>
                        <td>
                            {{ $payment->uploader->name }}
                            <br>
                            <small class="text-muted">{{ $payment->created_at->format('d M Y H:i') }}</small>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                @if($payment->status == 'pending')
                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($payment->paymentSlips->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bukti Pembayaran</h3>
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

        @if($payment->status == 'pending' && auth()->user()->can('validate-payments'))
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Validasi Pembayaran</h3>
                </div>
                <form action="{{ route('payments.validate', $payment) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Aksi <span class="text-danger">*</span></label>
                            <select name="action" class="form-control" required>
                                <option value="">-- Pilih Aksi --</option>
                                <option value="approve">Setujui</option>
                                <option value="reject">Tolak</option>
                            </select>
                        </div>
                        <div class="form-group" id="receipt-number-group" style="display: none;">
                            <label>Nomor Kwitansi <span class="text-danger">*</span></label>
                            <input type="text" name="receipt_number" class="form-control"
                                placeholder="Masukkan nomor kwitansi">
                        </div>
                        <div class="form-group" id="rejection-reason-group" style="display: none;">
                            <label>Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="3"
                                placeholder="Masukkan alasan penolakan"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-check"></i> Proses
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            $('select[name="action"]').on('change', function () {
                var action = $(this).val();
                if (action == 'approve') {
                    $('#receipt-number-group').show();
                    $('#rejection-reason-group').hide();
                    $('input[name="receipt_number"]').attr('required', true);
                    $('textarea[name="rejection_reason"]').attr('required', false);
                } else if (action == 'reject') {
                    $('#receipt-number-group').hide();
                    $('#rejection-reason-group').show();
                    $('input[name="receipt_number"]').attr('required', false);
                    $('textarea[name="rejection_reason"]').attr('required', true);
                } else {
                    $('#receipt-number-group').hide();
                    $('#rejection-reason-group').hide();
                }
            });
        });
    </script>
@endpush