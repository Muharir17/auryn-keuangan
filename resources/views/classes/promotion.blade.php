@extends('adminlte::page')

@section('title', 'Kenaikan Kelas')

@section('content_header')
<h1><i class="fas fa-level-up-alt"></i> Kenaikan / Kelulusan Kelas</h1>
@stop

@section('content')
<div class="row">
    {{-- Source Class Selection --}}
    <div class="col-md-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">1. Pilih Kelas Asal</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Kelas Asal</label>
                    <select name="source_class_id" id="source_class_id" class="form-control select2">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Pilih kelas yang siswanya akan dipindah/diluluskan.</small>
                </div>
            </div>
        </div>

        {{-- Action Selection --}}
        <div class="card card-outline card-success mt-3" id="action-card" style="display: none;">
            <div class="card-header">
                <h3 class="card-title">3. Pilih Aksi</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('classes.promotion.process') }}" method="POST" id="promotion-form">
                    @csrf
                    <input type="hidden" name="source_class_id" id="form_source_class_id">

                    <div class="form-group">
                        <label>Jenis Proses</label>
                        <select name="action" id="action" class="form-control">
                            <option value="promote">Naikkan ke Kelas Lain</option>
                            <option value="graduate">Luluskan (Alumni)</option>
                        </select>
                    </div>

                    <div class="form-group" id="target-class-group">
                        <label>Kelas Tujuan</label>
                        <select name="target_class_id" id="target_class_id" class="form-control select2">
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="student-inputs">
                        {{-- Checkboxes will be injected here --}}
                    </div>

                    <button type="submit" class="btn btn-success btn-block"
                        onclick="return confirm('Yakin ingin memproses data terpilih?')">
                        <i class="fas fa-check"></i> Proses Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Student List --}}
    <div class="col-md-8">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">2. Pilih Siswa</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-xs btn-default" id="select-all">Pilih Semua</button>
                    <button type="button" class="btn btn-xs btn-default" id="deselect-all">Hapus Pil.</button>
                </div>
            </div>
            <div class="card-body table-responsive p-0" style="height: 500px;">
                <table class="table table-head-fixed text-nowrap table-hover" id="students-table">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Nama Siswa</th>
                            <th>NIS</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Silakan pilih kelas asal terlebih dahulu.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="overlay" id="loading-overlay" style="display: none;">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        // Function to load students
        $('#source_class_id').change(function () {
            var classId = $(this).val();
            if (!classId) return;

            $('#form_source_class_id').val(classId);
            $('#loading-overlay').show();
            $('#action-card').show();

            $.ajax({
                url: '/classes/' + classId + '/students-list',
                method: 'GET',
                success: function (response) {
                    var rows = '';
                    var inputs = '';

                    if (response.length === 0) {
                        rows = '<tr><td colspan="4" class="text-center">Tidak ada siswa aktif di kelas ini.</td></tr>';
                    } else {
                        $.each(response, function (index, student) {
                            rows += '<tr>';
                            rows += '<td><div class="icheck-primary d-inline"><input type="checkbox" id="checkbox_' + student.id + '" class="student-checkbox" value="' + student.id + '"><label for="checkbox_' + student.id + '"></label></div></td>';
                            rows += '<td>' + student.name + '</td>';
                            rows += '<td>' + student.nis + '</td>';
                            rows += '<td><span class="badge badge-success">' + student.status + '</span></td>';
                            rows += '</tr>';
                        });
                    }

                    $('#students-table tbody').html(rows);
                    $('#loading-overlay').hide();
                },
                error: function () {
                    alert('Gagal mengambil data siswa.');
                    $('#loading-overlay').hide();
                }
            });
        });

        // Handle Action Change (Promote/Graduate)
        $('#action').change(function () {
            if ($(this).val() === 'graduate') {
                $('#target-class-group').hide();
                $('#target_class_id').prop('required', false);
            } else {
                $('#target-class-group').show();
                $('#target_class_id').prop('required', true);
            }
        });

        // Sync Checkboxes to Form
        $(document).on('change', '.student-checkbox', function () {
            updateFormInputs();
        });

        $('#select-all').click(function () {
            $('.student-checkbox').prop('checked', true);
            updateFormInputs();
        });

        $('#deselect-all').click(function () {
            $('.student-checkbox').prop('checked', false);
            updateFormInputs();
        });

        function updateFormInputs() {
            var inputs = '';
            $('.student-checkbox:checked').each(function () {
                inputs += '<input type="hidden" name="student_ids[]" value="' + $(this).val() + '">';
            });
            $('#student-inputs').html(inputs);

            // Also update button state
            if ($('.student-checkbox:checked').length === 0) {
                $('button[type="submit"]').prop('disabled', true);
            } else {
                $('button[type="submit"]').prop('disabled', false);
            }
        }
    });
</script>
@stop