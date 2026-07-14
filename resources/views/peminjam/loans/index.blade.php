@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat Peminjaman Saya</h4>
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
                                {{ $loan->tool->name ?? '-' }}
                                <br><small class="text-muted">{{ $loan->tool->code_slug ?? '' }}</small>
                            </td>
                            <td><code>{{ $loan->unit_code ?? '-' }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($loan->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($loan->status == 'borrowed')
                                    <span class="badge badge-primary">Dipinjam</span>
                                @elseif($loan->status == 'returned')
                                    <span class="badge badge-success">Dikembalikan</span>
                                @elseif($loan->status == 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-secondary">{{ $loan->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->status == 'pending')
                                    <a href="{{ route('peminjam.loans.edit', $loan) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('peminjam.loans.destroy', $loan) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan pengajuan?')">Hapus</button>
                                    </form>
                                @elseif($loan->status == 'borrowed')
                                    @php
                                        $returnExists = \App\Models\AssetReturn::where('loan_id', $loan->id)->exists();
                                    @endphp
                                    @if(!$returnExists)
                                        <a href="{{ route('peminjam.loans.return.create', $loan) }}" class="btn btn-sm btn-info">Ajukan Pengembalian</a>
                                    @else
                                        <span class="badge badge-warning">Pengembalian diajukan</span>
                                    @endif
                                @elseif($loan->status == 'returned')
                                    <span class="badge badge-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ti-folder"></i> Belum ada riwayat peminjaman.
                                    <br><a href="{{ route('peminjam.tools.index') }}" class="btn btn-sm btn-primary mt-2">Lihat Daftar Alat</a>
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