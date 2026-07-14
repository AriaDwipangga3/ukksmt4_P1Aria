@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Daftar Alat</h4>
        <a href="{{ route('admin.tools.create') }}" class="btn btn-primary btn-sm">+ Tambah Alat</a>
    </div>

    {{-- Filter & Search --}}
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('admin.tools.index') }}">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search"
                        class="form-control form-control-sm"
                        placeholder="Cari nama alat..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="category_id" class="form-control form-control-sm">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="item_type" class="form-control form-control-sm">
                        <option value="">-- Semua Tipe --</option>
                        <option value="single"      {{ request('item_type') == 'single'      ? 'selected' : '' }}>Single</option>
                        <option value="bundle"      {{ request('item_type') == 'bundle'      ? 'selected' : '' }}>Bundle</option>
                        <option value="bundle_tool" {{ request('item_type') == 'bundle_tool' ? 'selected' : '' }}>Bundle Tool</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-secondary btn-sm mr-1">Cari</button>
                    <a href="{{ route('admin.tools.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </div>
        </form>
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
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Min. Credit</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tools as $tool)
                        <tr>
                            <td>{{ ($tools->currentPage() - 1) * $tools->perPage() + $loop->iteration }}</td>

                            <td>
                                {{ $tool->name }}
                                <br><small class="text-muted">{{ $tool->code_slug }}</small>
                            </td>

                            <td>{{ optional($tool->category)->name ?? '-' }}</td>

                            <td>
                                @if($tool->item_type === 'single')
                                    <span class="badge badge-primary">Single</span>
                                @elseif($tool->item_type === 'bundle')
                                    <span class="badge badge-info">Bundle</span>
                                @elseif($tool->item_type === 'bundle_tool')
                                    <span class="badge badge-secondary">Bundle Tool</span>
                                @endif
                            </td>

                            <td>Rp {{ number_format($tool->price, 0, ',', '.') }}</td>

                            <td>
                                @if($tool->min_credit_score > 0)
                                    <span class="badge badge-warning">{{ $tool->min_credit_score }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if($tool->photo_path)
                                    <img src="{{ asset('storage/' . $tool->photo_path) }}"
                                        width="50" height="50"
                                        style="object-fit:cover; border-radius:4px;">
                                @else
                                    <span class="badge badge-secondary">Tidak ada</span>
                                @endif
                            </td>

                            <td>
                                <!-- Tombol Unit (baru) -->
                                <a href="{{ route('admin.tools.units.index', $tool) }}"
                                   class="btn btn-sm btn-info">Unit</a>

                                <a href="{{ route('admin.tools.edit', $tool) }}"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.tools.destroy', $tool) }}"
                                    method="POST" style="display:inline-block"
                                    onsubmit="return confirm('Yakin hapus alat {{ $tool->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data alat
                                @if(request('search') || request('category_id') || request('item_type'))
                                    yang cocok dengan filter.
                                    <a href="{{ route('admin.tools.index') }}">Reset filter</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tools->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan {{ $tools->firstItem() ?? 0 }} - {{ $tools->lastItem() ?? 0 }}
                    dari {{ $tools->total() }} data
                </small>
                {{ $tools->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
</div>

@endsection