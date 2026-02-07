@extends('adminlte::page')

@section('title', 'Transaksi BOS')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-exchange-alt"></i> Transaksi BOS</h1>
    </div>
    <div class="col-sm-6">
        <a href="{{ route('bos.transactions.pdf', request()->all()) }}" class="btn btn-danger float-right"
            target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Transaksi BOS</h3>
        <div class="card-tools">
            <button class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Tambah Transaksi</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>
                            @if($transaction->type == 'income')
                                <span class="badge badge-success"><i class="fas fa-arrow-down"></i> Pemasukan</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-arrow-up"></i> Pengeluaran</span>
                            @endif
                        </td>
                        <td class="text-{{ $transaction->type == 'income' ? 'success' : 'danger' }}">
                            <strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong>
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