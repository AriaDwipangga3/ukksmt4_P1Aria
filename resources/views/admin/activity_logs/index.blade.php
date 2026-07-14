@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="icon-clock"></i> Log Aktivitas
            </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="50">#</th>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Modul</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                {{ $log->user ? $log->user->name : 'Sistem' }}
                                @if($log->user)
                                    <br><small class="text-muted">{{ $log->user->email }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $log->action == 'login' ? 'bg-success' : ($log->action == 'logout' ? 'bg-secondary' : 'bg-primary') }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>
                                @if($log->module)
                                    <span class="badge bg-info">{{ $log->module }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="icon-database"></i> Belum ada aktivitas tercatat
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination dengan informasi jumlah data -->
            @if($logs->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                    <div class="text-muted small">
                        Menampilkan {{ $logs->firstItem() ?? 0 }} – {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data
                    </div>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection