@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Data Peminjaman (Admin)</h4>
            <a href="{{ route('admin.loans_crud.create') }}" class="btn btn-primary btn-sm">+ Tambah Peminjaman</a>
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
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $index => $loan)
                        <tr>
                            <td>{{ $loop->iteration + ($loans->currentPage() - 1) * $loans->perPage() }}</td>
                            <td>
                                {{ $loan->user->name ?? '-' }}
                                <br><small class="text-muted">{{ $loan->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $loan->tool->name ?? '-' }}<br><small>{{ $loan->tool->code_slug ?? '' }}</small></td>
                            <td><code>{{ $loan->unit_code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge 
                                    @if($loan->status == 'pending') badge-warning
                                    @elseif($loan->status == 'approved') badge-primary
                                    @elseif($loan->status == 'rejected') badge-danger
                                    @elseif($loan->status == 'borrowed') badge-info
                                    @elseif($loan->status == 'returned') badge-success
                                    @else badge-secondary @endif">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.loans_crud.edit', $loan) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.loans_crud.destroy', $loan) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus peminjaman #{{ $loan->id }}?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="ti-folder"></i> Belum ada data peminjaman.
                                    <br><a href="{{ route('admin.loans_crud.create') }}" class="btn btn-sm btn-primary mt-2">Tambah Peminjaman</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($loans->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $loans->firstItem() }} - {{ $loans->lastItem() }}
                        dari {{ $loans->total() }} data
                    </small>
                    {{ $loans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection