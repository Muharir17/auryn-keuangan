@extends('adminlte::page')

@section('title', 'Daftar Proposal')

@section('content_header')
<h1><i class="fas fa-file-alt"></i> Daftar Proposal</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Semua Proposal</h3>
        <div class="card-tools">
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Buat Proposal</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Judul Proposal</th>
                    <th>Diajukan Oleh</th>
                    <th>Anggaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposals as $proposal)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($proposal->date)->format('d M Y') }}</td>
                        <td><strong>{{ $proposal->title }}</strong></td>
                        <td>{{ $proposal->submitter->name }}</td>
                        <td>Rp {{ number_format($proposal->amount, 0, ',', '.') }}</td>
                        <td>
                            @if($proposal->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($proposal->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop