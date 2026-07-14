@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Edit Unit: {{ $unit->code }}</h4></div>
    <div class="card-body">
        <form action="{{ route('admin.tools.units.update', [$tool, $unit]) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Kode Unit *</label>
                <input type="text" name="code" class="form-control" value="{{ $unit->code }}" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="available" {{ $unit->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="borrowed" {{ $unit->status == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="damaged" {{ $unit->status == 'damaged' ? 'selected' : '' }}>Rusak</option>
                    <option value="maintenance" {{ $unit->status == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="notes" class="form-control" rows="2">{{ $unit->notes }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.tools.units.index', $tool) }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection