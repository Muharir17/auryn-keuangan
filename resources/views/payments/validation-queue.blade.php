@extends('adminlte::page')

@section('title', 'Validasi Pembayaran')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Validasi Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
                <li class="breadcrumb-item active">Validasi Queue</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Antrian Validasi Pembayaran</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $pendingPayments->total() }} Pending</span>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($pendingPayments as $index => $payment)
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            {{ $payment->student->name }} - {{ $payment->student->class->name }}
                                            <span class="badge badge-warning float-right">Pending</span>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Tagihan:</strong> {{ $payment->bill->paymentType->name }}</p>
                                                <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                <p><strong>Tanggal:</strong> {{ $payment->payment_date->format('d M Y') }}</p>
                                                <p><strong>Metode:</strong> {{ ucfirst($payment->payment_method) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Diunggah oleh:</strong> {{ $payment->paymentSlips->first()->uploader->name }}</p>
                                                <p><strong>Tanggal Upload:</strong> {{ $payment->created_at->format('d M Y H:i:s') }}</p>
                                                @if($payment->notes)
                                                    <p><strong>Catatan:</strong> {{ $payment->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Payment Slips Preview -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6>Bukti Pembayaran:</h6>
                                                <div class="row">
                                                    @foreach($payment->paymentSlips as $slip)
                                                        <div class="col-md-4 mb-3">
                                                            <img src="{{ asset('storage/' . $slip->file_path) }}" class="img-fluid rounded" alt="Bukti Pembayaran" style="max-height: 200px; cursor: pointer;" onclick="window.open('{{ asset('storage/' . $slip->file_path) }}', '_blank')">
                                                            <small class="d-block text-center">{{ $slip->original_filename }}</small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <form action="{{ route('payments.validate', $payment) }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-success btn-block" onclick="approvePayment(this.form)">
                                                        <i class="fas fa-check"></i> Setujui
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="button" class="btn btn-danger btn-block" onclick="rejectPayment(this.form)">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Hidden fields for validation -->
                                            <input type="hidden" name="action" id="action-{{ $payment->id }}">
                                            <div id="receipt-field-{{ $payment->id }}" class="d-none">
                                                <div class="form-group mt-3">
                                                    <label>Nomor Bukti:</label>
                                                    <input type="text" name="receipt_number" class="form-control" placeholder="Masukkan nomor bukti pembayaran">
                                                </div>
                                            </div>
                                            <div id="reason-field-{{ $payment->id }}" class="d-none">
                                                <div class="form-group mt-3">
                                                    <label>Alasan Penolakan:</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="2" placeholder="Masukkan alasan penolakan"></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h4>Tidak Ada Pembayaran Pending</h4>
                            <p class="text-muted">Semua pembayaran sudah divalidasi.</p>
                            <a href="{{ route('payments.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> Lihat Semua Pembayaran
                            </a>
                        </div>
                    @endforelse
                </div>
                @if($pendingPayments->hasPages())
                    <div class="card-footer">
                        {{ $pendingPayments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
function approvePayment(form) {
    $('#action-' + form.id.replace('validate-', '')).val('approve');
    $('#receipt-field-' + form.id.replace('validate-', '')).removeClass('d-none');
    $('#reason-field-' + form.id.replace('validate-', '')).addClass('d-none');
    
    if(confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
        form.submit();
    }
}

function rejectPayment(form) {
    $('#action-' + form.id.replace('validate-', '')).val('reject');
    $('#reason-field-' + form.id.replace('validate-', '')).removeClass('d-none');
    $('#receipt-field-' + form.id.replace('validate-', '')).addClass('d-none');
    
    var reasonField = form.querySelector('textarea[name="rejection_reason"]');
    if(!reasonField.value) {
        alert('Alasan penolakan harus diisi!');
        reasonField.focus();
        return;
    }
    
    if(confirm('Apakah Anda yakin ingin menolak pembayaran ini?')) {
        form.submit();
    }
}
</script>
@endpush
