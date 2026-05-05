@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Alat</h4>
                    <a href="{{ route('admin.tools.create') }}" class="btn btn-primary btn-sm">Tambah Alat</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th><th>Nama</th><th>Kategori</th><th>Tipe</th><th>Harga</th><th>Foto</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tools as $tool)
                                <tr>
                                    <td>{{ $tool->id }}</td>
                                    <td>{{ $tool->name }}</td>
                                    <td>{{ $tool->category->name ?? '-' }}</td>
                                    <td>{{ ucfirst($tool->item_type) }}</td>
                                    <td>Rp {{ number_format($tool->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($tool->photo_path)
                                            <img src="{{ asset('storage/' . $tool->photo_path) }}" width="50" height="50" style="object-fit:cover;">
                                        @else
                                            <span class="badge badge-secondary">No image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.tools.edit', $tool) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center">Data alat kosong</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $tools->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection