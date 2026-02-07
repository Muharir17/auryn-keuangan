@extends('adminlte::page')

@section('title', 'Audit Logs')

@section('content_header')
<h1><i class="fas fa-history"></i> Audit Logs</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Activity Logs</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Fitur audit log akan diintegrasikan dengan package
            <code>spatie/laravel-activitylog</code>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $log->user }}</td>
                        <td>
                            @if($log->action == 'Created')
                                <span class="badge badge-success">{{ $log->action }}</span>
                            @elseif($log->action == 'Updated')
                                <span class="badge badge-info">{{ $log->action }}</span>
                            @elseif($log->action == 'Deleted')
                                <span class="badge badge-danger">{{ $log->action }}</span>
                            @else
                                <span class="badge badge-secondary">{{ $log->action }}</span>
                            @endif
                        </td>
                        <td><code>{{ $log->model }}</code></td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop