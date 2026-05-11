@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h4>Tambah Pelanggaran</h4></div>
        <div class="card-body">
            <form action="{{ route('petugas.violations.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Peminjaman (Peminjam - Alat)</label>
                    <select name="loan_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($loans as $loan)
                            <option value="{{ $loan->id }}">{{ $loan->user->name }} - {{ $loan->tool->name }} ({{ $loan->unit_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipe Pelanggaran</label>
                    <select name="type" class="form-control" required>
                        <option value="late">Terlambat</option>
                        <option value="damaged">Rusak</option>
                        <option value="lost">Hilang</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Denda (Rp)</label>
                    <input type="number" name="fine" class="form-control" required min="0">
                </div>
                <div class="form-group">
                    <label>Point Pelanggaran (opsional)</label>
                    <input type="number" name="score" class="form-control" min="0">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('petugas.violations.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection