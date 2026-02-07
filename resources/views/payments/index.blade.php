@extends('adminlte::page')

@section('title', 'Manajemen Pembayaran')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Manajemen Pembayaran</h1>
    </div>
    <div class="col-sm-6 text-right">
        @can('view-reports')
        <a href="{{ route('reports.daily.pdf') }}" id="btn-daily-report" class="btn btn-secondary mr-2" target="_blank">
             <i class="fas fa-file-pdf"></i> Cetak Laporan Harian
        </a>
        @endcan
        @can('create-payments')
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Input Pembayaran Baru
        </a>
        @endcan
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pembayaran</h3>
                <div class="card-tools">
                    @can('validate-payments')
                        <a href="{{ route('payments.validation-queue') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-clock"></i> Validasi Queue
                        </a>
                    @endcan
                    @can('bulk-upload')
                        <a href="{{ route('payments.bulk-upload') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-upload"></i> Bulk Upload
                        </a>
                    @endcan
                    <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pembayaran
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-control" id="filter-status">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filter-class">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="filter-date" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="search-student" placeholder="Cari Nama Siswa">
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <td>{{ $payment->student->name }}</td>
                                <td>{{ $payment->student->class->name }}</td>
                                <td>{{ $payment->bill->paymentType->name }}</td>
                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($payment->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($payment->status == 'approved')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($payment->status == 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->paymentSlips->isNotEmpty())
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                            data-target="#slipModal{{ $payment->id }}">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('payments.print', $payment) }}" class="btn btn-secondary btn-sm"
                                            title="Cetak Kwitansi" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-info btn-sm"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->status == 'pending')
                                            @can('validate-payments')
                                                <a href="{{ route('payments.validate', $payment) }}" class="btn btn-success btn-sm"
                                                    title="Validasi">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @endcan
                                        @endif
                                        @if($payment->status == 'pending')
                                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($payment->status == 'pending')
                                            <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Payment Slip Modals -->
@foreach($payments as $payment)
    @if($payment->paymentSlips->isNotEmpty())
        <div class="modal fade" id="slipModal{{ $payment->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Pembayaran - {{ $payment->student->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @foreach($payment->paymentSlips as $slip)
                            <div class="mb-3">
                                <h6>{{ $slip->original_filename }}</h6>
                                <img src="{{ asset('storage/' . $slip->file_path) }}" class="img-fluid" alt="Bukti Pembayaran">
                                <small class="text-muted">
                                    Diunggah: {{ $slip->created_at->format('d M Y H:i:s') }} oleh {{ $slip->uploader->name }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            // Update Daily Report Button URL when date changes
            $('#filter-date').on('change', function () {
                var date = $(this).val();
                var baseUrl = '{{ route('reports.daily.pdf') }}';
                if (date) {
                    $('#btn-daily-report').attr('href', baseUrl + '?date=' + date);
                } else {
                    $('#btn-daily-report').attr('href', baseUrl);
                }
            });

            // Filter functionality
            $('#filter-status, #filter-class, #filter-date, #search-student').on('change keyup', function () {
                var status = $('#filter-status').val();
                var classId = $('#filter-class').val();
                var date = $('#filter-date').val();
                var search = $('#search-student').val();

                var url = '{{ route('payments.index') }}?';
                if (status) url += 'status=' + status + '&';
                if (classId) url += 'class=' + classId + '&';
                if (date) url += 'date=' + date + '&';
                if (search) url += 'search=' + search;

                window.location.href = url;
            });
        });
    </script>
@endpush