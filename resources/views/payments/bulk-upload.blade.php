@extends('adminlte::page')

@section('title', 'Bulk Upload Pembayaran')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Bulk Upload Pembayaran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
                <li class="breadcrumb-item active">Bulk Upload</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Bukti Pembayaran Massal</h3>
                    <div class="card-tools">
                        <a href="{{ route('payments.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <form action="{{ route('payments.bulk-upload.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Petunjuk:</strong> Upload bukti pembayaran untuk seluruh siswa dalam satu kelas. File akan diproses secara manual di halaman validasi.
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
                            <label for="payment_slips" class="col-sm-4 col-form-label">File Bukti Pembayaran <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="custom-file">
                                    <input type="file" name="payment_slips[]" id="payment_slips" class="custom-file-input @error('payment_slips') is-invalid @enderror" accept="image/*,.pdf" multiple required>
                                    <label class="custom-file-label" for="payment_slips">Pilih file bukti pembayaran...</label>
                                </div>
                                <small class="form-text text-muted">
                                    <strong>Ketentuan:</strong><br>
                                    • Format: JPG, PNG, PDF<br>
                                    • Maksimal: 5MB per file<br>
                                            • Bisa upload multiple file<br>
                                            • Nama file sebaiknya mengandung nama siswa untuk kemudahan matching
                                        </small>
                                        @error('payment_slips')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Preview File</label>
                                    <div class="col-sm-8">
                                        <div id="file-preview" class="row">
                                            <!-- File previews will be shown here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Instructions Card -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Cara Penggunaan</h5>
                        </div>
                        <div class="card-body">
                            <h6><i class="fas fa-list-ol"></i> Langkah 1: Pilih Kelas</h6>
                            <p>Pilih kelas yang siswanya akan diupload bukti pembayarannya.</p>
                            
                            <h6><i class="fas fa-list-ol"></i> Langkah 2: Upload File</h6>
                            <p>Upload semua bukti pembayaran siswa dalam satu kali proses.</p>
                            
                            <h6><i class="fas fa-list-ol"></i> Langkah 3: Validasi Manual</h6>
                            <p>Setelah upload, lakukan matching manual di halaman validasi pembayaran.</p>
                            
                            <hr>
                            
                            <h6><i class="fas fa-lightbulb"></i> Tips:</h6>
                            <ul>
                                <li>Beri nama file dengan format: NIS_NamaSiswa_JenisPembayaran</li>
                                <li>Upload file yang jelas dan mudah dibaca</li>
                                <li>Satukan file pembayaran yang sama dalam satu upload</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Status Upload</h5>
                        </div>
                        <div class="card-body">
                            <div id="upload-status">
                                <p class="text-muted">Belum ada file yang diupload.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();
    
    // File preview functionality
    $('#payment_slips').on('change', function(e) {
        var files = e.target.files;
        var preview = $('#file-preview');
        preview.empty();
        
        if(files.length > 0) {
            $('#upload-status').html('<p class="text-info"><i class="fas fa-spinner fa-spin"></i> ' + files.length + ' file dipilih</p>');
            
            for(var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    var fileType = file.type;
                    var previewHtml = '';
                    
                    if(fileType.startsWith('image/')) {
                        previewHtml = '<div class="col-md-6 mb-2">' +
                            '<img src="' + e.target.result + '" class="img-fluid rounded border" style="max-height: 150px;">' +
                            '<small class="d-block text-truncate">' + file.name + '</small>' +
                            '<small class="text-muted">' + formatFileSize(file.size) + '</small>' +
                            '</div>';
                    } else {
                        previewHtml = '<div class="col-md-6 mb-2">' +
                            '<div class="border rounded p-3 text-center" style="height: 150px;">' +
                            '<i class="fas fa-file-pdf fa-3x text-danger"></i>' +
                            '<div class="mt-2"><small class="d-block text-truncate">' + file.name + '</small></div>' +
                            '<small class="text-muted">' + formatFileSize(file.size) + '</small>' +
                            '</div>' +
                            '</div>';
                    }
                    
                    preview.append(previewHtml);
                };
                
                reader.readAsDataURL(file);
            }
        } else {
            $('#upload-status').html('<p class="text-muted">Belum ada file yang diupload.</p>');
        }
        
        // Update file label
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file bukti pembayaran...');
    });
    
    function formatFileSize(bytes) {
        if(bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush
