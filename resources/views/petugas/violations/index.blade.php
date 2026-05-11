@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Pelanggaran & Denda</h4>
            <a href="{{ route('petugas.violations.create') }}" class="btn btn-primary btn-sm">Tambah Pelanggaran</a>
        </div>
        <div class="card-body">
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            <form method="GET" class="row mb-3">
                <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Cari peminjam" value="{{ request('search') }}"></div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Belum Bayar</option>
                        <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">Semua Tipe</option>
                        <option value="late" {{ request('type')=='late'?'selected':'' }}>Terlambat</option>
                        <option value="damaged" {{ request('type')=='damaged'?'selected':'' }}>Rusak</option>
                        <option value="lost" {{ request('type')=='lost'?'selected':'' }}>Hilang</option>
                    </select>
                </div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary">Filter</button> <a href="{{ route('petugas.violations.index') }}" class="btn btn-secondary">Reset</a></div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>ID</th><th>Peminjam</th><th>Alat</th><th>Tipe</th><th>Denda</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $v)
                        <tr>
                            <td>{{ $v->id }}</td>
                            <td>{{ $v->user->name ?? '-' }} <br><small>{{ $v->user->email ?? '' }}</small></td>
                            <td>{{ $v->loan->tool->name ?? '-' }} ({{ $v->loan->unit_code ?? '' }})</td>
                            <td>
                                @if($v->type == 'late') Terlambat
                                @elseif($v->type == 'damaged') Rusak
                                @else Hilang @endif
                            </td>
                            <td>Rp {{ number_format($v->fine,0,',','.') }}</td>
                            <td>
                                @if($v->status == 'paid') <span class="badge badge-success">Lunas</span>
                                @else <span class="badge badge-danger">Belum Bayar</span> @endif
                            </td>
                            <td>
                                <a href="{{ route('petugas.violations.edit', $v) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('petugas.violations.destroy', $v) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $violations->links() }}
        </div>
    </div>
</div>
@endsection