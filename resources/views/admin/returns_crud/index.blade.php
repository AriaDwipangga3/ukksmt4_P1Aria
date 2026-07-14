@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Data Pengembalian (Admin)</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Tgl Kembali</th>
                            <th>Kondisi</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $index => $return)
                        <tr>
                            <td>{{ $loop->iteration + ($returns->currentPage() - 1) * $returns->perPage() }}</td>
                            <td>
                                {{ $return->loan->user->name ?? '-' }}
                                <br><small class="text-muted">{{ $return->loan->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $return->loan->tool->name ?? '-' }}<br><small>{{ $return->loan->tool->code_slug ?? '' }}</small></td>
                            <td><code>{{ $return->loan->unit_code ?? '-' }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($return->condition == 'good') <span class="badge badge-success">Baik</span>
                                @elseif($return->condition == 'damaged') <span class="badge badge-danger">Rusak</span>
                                @elseif($return->condition == 'lost') <span class="badge badge-dark">Hilang</span>
                                @else <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td><strong>Rp {{ number_format($return->fine, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($return->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($return->status == 'processed')
                                    <span class="badge badge-success">Diproses</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($return->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.returns_crud.edit', $return) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.returns_crud.destroy', $return) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengembalian #{{ $return->id }}?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="ti-folder"></i> Belum ada data pengembalian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returns->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $returns->firstItem() }} - {{ $returns->lastItem() }}
                        dari {{ $returns->total() }} data
                    </small>
                    {{ $returns->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection