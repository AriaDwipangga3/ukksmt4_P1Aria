@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Log Aktivitas</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Modul</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $log->user ? $log->user->name : 'Sistem' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->module }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                            </td><td colspan="6" class="text-center">Belum ada aktivitas recorded</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection