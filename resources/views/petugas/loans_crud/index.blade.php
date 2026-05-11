@extends('layouts.app')
@section('content')

<div class="container-fluid">

    {{-- Alert --}}
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

    <div class="card">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manajemen Peminjaman</h4>
            <a href="{{ route('petugas.loans_crud.create') }}" class="btn btn-primary btn-sm">
                + Tambah Peminjaman
            </a>
        </div>

        {{-- Filter & Search --}}
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('petugas.loans_crud.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input
                            type="text"
                            name="search"
                            class="form-control form-control-sm"
                            placeholder="Cari nama peminjam / alat..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">-- Semua Status --</option>
                            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="active"    {{ request('status') == 'active'    ? 'selected' : '' }}>Aktif</option>
                            <option value="returned"  {{ request('status') == 'returned'  ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-secondary btn-sm mr-1">Cari</button>
                        <a href="{{ route('petugas.loans_crud.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40">#</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                {{-- Nomor urut --}}
                                <td>{{ ($loans->currentPage() - 1) * $loans->perPage() + $loop->iteration }}</td>

                                {{-- Peminjam --}}
                                <td>
                                    @if($loan->user)
                                        {{ optional($loan->user->detail)->name ?? $loan->user->email }}
                                        <br><small class="text-muted">{{ $loan->user->email }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Alat --}}
                                <td>{{ optional($loan->tool)->name ?? '-' }}</td>

                                {{-- Unit --}}
                                <td><code>{{ $loan->unit_code ?? '-' }}</code></td>

                                {{-- Tgl Pinjam --}}
                                <td>
                                    @if($loan->loan_date)
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Tgl Kembali + badge terlambat --}}
                                <td>
                                    @if($loan->due_date)
                                        {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}
                                        @if($loan->status === 'active' && \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($loan->due_date)))
                                            <br>
                                            <span class="badge badge-danger">
                                                Terlambat {{ \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($loan->due_date)) }} hari
                                            </span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($loan->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($loan->status === 'active')
                                        <span class="badge badge-primary">Aktif</span>
                                    @elseif($loan->status === 'returned')
                                        <span class="badge badge-success">Dikembalikan</span>
                                    @elseif($loan->status === 'rejected')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @elseif($loan->status === 'cancelled')
                                        <span class="badge badge-secondary">Dibatalkan</span>
                                    @else
                                        <span class="badge badge-light">{{ $loan->status }}</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td>
                                    <a href="{{ route('petugas.loans_crud.edit', $loan->id) }}"
                                        class="btn btn-warning btn-xs">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('petugas.loans_crud.destroy', $loan->id) }}"
                                        method="POST"
                                        style="display:inline-block"
                                        onsubmit="return confirm('Yakin hapus peminjaman #{{ $loan->id }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Tidak ada data peminjaman
                                    @if(request('search') || request('status'))
                                        yang cocok dengan filter.
                                        <a href="{{ route('petugas.loans_crud.index') }}">Reset filter</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($loans->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan
                        {{ $loans->firstItem() ?? 0 }} - {{ $loans->lastItem() ?? 0 }}
                        dari {{ $loans->total() }} data
                    </small>
                    <div>
                        {{ $loans->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection