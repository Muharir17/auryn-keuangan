@extends('adminlte::page')

@section('title', 'Budget BOS')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-wallet"></i> Budget BOS</h1>
    </div>
    <div class="col-sm-6">
        <a href="{{ route('bos.budgets.pdf') }}" class="btn btn-danger float-right" target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Budget BOS</h3>
        <div class="card-tools">
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Budget</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Total Budget</th>
                    <th>Terpakai</th>
                    <th>Sisa</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgets as $budget)
                    <tr>
                        <td><strong>{{ $budget->year }}</strong></td>
                        <td>Rp {{ number_format($budget->amount, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($budget->used, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($budget->remaining, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $percentage = $budget->progress_percentage;
                            @endphp
                            <div class="progress">
                                <div class="progress-bar bg-{{ $percentage > 80 ? 'danger' : ($percentage > 50 ? 'warning' : 'success') }}"
                                    style="width: {{ $percentage }}%">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop