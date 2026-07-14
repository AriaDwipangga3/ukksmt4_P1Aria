@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Pelanggaran & Denda</h4>
            <a href="{{ route('petugas.violations.create') }}" class="btn btn-primary btn-sm">
                + Tambah Pelanggaran
            </a>
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

            {{-- Filter --}}
            <form method="GET" class="row mb-3">
                <div class="col-md-3 mb-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari nama / email peminjam..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="unpaid"  {{ request('status') == 'unpaid'  ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid"    {{ request('status') == 'paid'    ? 'selected' : '' }}>Sudah Bayar</option>
                        <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="type" class="form-control form-control-sm">
                        <option value="">Semua Tipe</option>
                        <option value="late"    {{ request('type') == 'late'    ? 'selected' : '' }}>Terlambat</option>
                        <option value="damaged" {{ request('type') == 'damaged' ? 'selected' : '' }}>Rusak</option>
                        <option value="lost"    {{ request('type') == 'lost'    ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-secondary btn-sm mr-1">Filter</button>
                    <a href="{{ route('petugas.violations.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tipe</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $v)
                            <tr>
                                <td>{{ ($violations->currentPage() - 1) * $violations->perPage() + $loop->iteration }}</td>

                                {{-- Nama dari kolom 'name' di tabel users langsung --}}
                                <td>
                                    {{ $v->user->name ?? '-' }}
                                    <br><small class="text-muted">{{ $v->user->email ?? '' }}</small>
                                </td>

                                <td>
                                    {{ optional(optional($v->loan)->tool)->name ?? '-' }}
                                    <br><code>{{ optional($v->loan)->unit_code ?? '' }}</code>
                                </td>

                                <td>
                                    @if($v->type === 'late')
                                        <span class="badge badge-warning">Terlambat</span>
                                    @elseif($v->type === 'damaged')
                                        <span class="badge" style="background:#fd7e14;color:#fff">Rusak</span>
                                    @else
                                        <span class="badge badge-danger">Hilang</span>
                                    @endif
                                </td>

                                <td>Rp {{ number_format($v->fine, 0, ',', '.') }}</td>

                                <td>
                                    @if($v->status === 'unpaid')
                                        <span class="badge badge-danger">Belum Bayar</span>
                                    @elseif($v->status === 'paid')
                                        <span class="badge badge-info">Sudah Bayar</span>
                                    @else
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('petugas.violations.edit', $v) }}"
                                        class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('petugas.violations.destroy', $v) }}"
                                        method="POST" style="display:inline-block"
                                        onsubmit="return confirm('Yakin hapus pelanggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Tidak ada data pelanggaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($violations->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $violations->firstItem() }} - {{ $violations->lastItem() }}
                        dari {{ $violations->total() }} data
                    </small>
                    {{ $violations->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

@endsection