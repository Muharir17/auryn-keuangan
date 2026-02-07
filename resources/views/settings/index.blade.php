@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
<h1><i class="fas fa-cog"></i> Settings</h1>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Aplikasi</h3>
            </div>
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Aplikasi</label>
                        <input type="text" class="form-control" name="app_name" value="{{ $settings['app_name'] }}">
                    </div>

                    <div class="form-group">
                        <label>Nama Sekolah</label>
                        <input type="text" class="form-control" name="school_name"
                            value="{{ $settings['school_name'] }}">
                    </div>

                    <div class="form-group">
                        <label>Alamat Sekolah</label>
                        <textarea class="form-control" name="school_address"
                            rows="3">{{ $settings['school_address'] }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" class="form-control" name="school_phone"
                                    value="{{ $settings['school_phone'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="school_email"
                                    value="{{ $settings['school_email'] }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <input type="text" class="form-control" name="academic_year"
                            value="{{ $settings['academic_year'] }}">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Sistem</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>Laravel Version</th>
                        <td>{{ app()->version() }}</td>
                    </tr>
                    <tr>
                        <th>PHP Version</th>
                        <td>{{ phpversion() }}</td>
                    </tr>
                    <tr>
                        <th>Environment</th>
                        <td><span
                                class="badge badge-{{ app()->environment() == 'production' ? 'success' : 'warning' }}">{{ app()->environment() }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning">
                <h3 class="card-title">Maintenance Mode</h3>
            </div>
            <div class="card-body">
                <p>Aktifkan mode maintenance untuk melakukan update sistem.</p>
                <button class="btn btn-warning btn-block">
                    <i class="fas fa-tools"></i> Enable Maintenance
                </button>
            </div>
        </div>
    </div>
</div>
@stop