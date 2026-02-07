@extends('adminlte::page')

@section('title', 'Approval Proposal')

@section('content_header')
<h1><i class="fas fa-check-circle"></i> Approval Proposal</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Proposal Menunggu Approval</h3>
    </div>
    <div class="card-body">
        @if($proposals->count() > 0)
            @foreach($proposals as $proposal)
                <div class="card mb-3">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">{{ $proposal->title }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="150">Diajukan Oleh</th>
                                        <td>{{ $proposal->submitter->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td>{{ \Carbon\Carbon::parse($proposal->date)->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Anggaran</th>
                                        <td><strong class="text-primary">Rp
                                                {{ number_format($proposal->amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6 text-right">
                                <button class="btn btn-success btn-lg">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn btn-danger btn-lg">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                                <button class="btn btn-info btn-lg">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tidak ada proposal yang menunggu approval.
            </div>
        @endif
    </div>
</div>
@stop