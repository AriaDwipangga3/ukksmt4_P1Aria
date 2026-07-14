@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Tambah Unit untuk {{ $tool->name }}</h4></div>
    <div class="card-body">
        <form action="{{ route('admin.tools.units.store', $tool) }}" method="POST">
            @csrf

            {{-- Informasi generate otomatis --}}
            <div class="alert alert-info">
                <strong>Kode Unit akan dibuat otomatis</strong><br>
                Format: <code>{{ Str::slug($tool->name) }}-001</code>, lalu <code>...-002</code>, dan seterusnya.
            </div>

            {{-- Field status --}}
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="available">Tersedia</option>
                    <option value="borrowed">Dipinjam</option>
                    <option value="damaged">Rusak</option>
                    <option value="maintenance">Perawatan</option>
                </select>
            </div>

            {{-- Catatan --}}
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.tools.units.index', $tool) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection