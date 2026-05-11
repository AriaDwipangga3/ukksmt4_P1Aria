@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h4>Edit Pelanggaran #{{ $violation->id }}</h4></div>
        <div class="card-body">
            <form action="{{ route('petugas.violations.update', $violation) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Peminjam</label>
                    <input type="text" class="form-control" value="{{ $violation->user->name ?? '-' }} ({{ $violation->user->email ?? '-' }})" disabled>
                </div>
                <div class="form-group">
                    <label>Alat / Unit</label>
                    <input type="text" class="form-control" value="{{ $violation->loan->tool->name ?? '-' }} ({{ $violation->loan->unit_code ?? '-' }})" disabled>
                </div>
                <div class="form-group">
                    <label>Tipe Pelanggaran</label>
                    <select name="type" class="form-control" required>
                        <option value="late" {{ $violation->type=='late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="damaged" {{ $violation->type=='damaged' ? 'selected' : '' }}>Rusak</option>
                        <option value="lost" {{ $violation->type=='lost' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Denda (Rp)</label>
                    <input type="number" name="fine" class="form-control" value="{{ $violation->fine }}" required min="0">
                </div>
                <div class="form-group">
                    <label>Point Pelanggaran</label>
                    <input type="number" name="score" class="form-control" value="{{ $violation->score }}" min="0">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ $violation->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="unpaid" {{ $violation->status=='unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid" {{ $violation->status=='paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('petugas.violations.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection