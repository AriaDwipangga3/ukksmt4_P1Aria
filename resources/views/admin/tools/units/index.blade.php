@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4 class="card-title">Manajemen Unit: {{ $tool->name }}</h4>
        <a href="{{ route('admin.tools.units.create', $tool) }}" class="btn btn-primary btn-sm">+ Tambah Unit</a>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr><th>Kode Unit</th><th>Status</th><th>Catatan</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td>{{ $unit->code }}</td>
                        <td>
                            <span class="badge 
                                {{ $unit->status == 'available' ? 'badge-success' : 
                                   ($unit->status == 'borrowed' ? 'badge-warning' : 
                                   ($unit->status == 'damaged' ? 'badge-danger' : 'badge-secondary')) }}">
                                {{ ucfirst($unit->status) }}
                            </span>
                        </td>
                        <td>{{ $unit->notes ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.tools.units.edit', [$tool, $unit]) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.tools.units.destroy', [$tool, $unit]) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus unit ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4">Tidak ada unit untuk alat ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $units->links() }}
    </div>
</div>
@endsection