@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Edit Pengajuan Peminjaman</h4></div>
    <div class="card-body">
        <form action="{{ route('peminjam.loans.update', $loan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Unit Alat</label>
                <select name="unit_code" class="form-control" required>
                    @foreach($availableUnits as $unit)
                        <option value="{{ $unit->code }}" {{ $loan->unit_code == $unit->code ? 'selected' : '' }}>
                            {{ $unit->code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="loan_date" class="form-control" value="{{ old('loan_date', $loan->loan_date) }}" required>
            </div>
            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $loan->due_date) }}" required>
            </div>
            <div class="form-group">
                <label>Tujuan</label>
                <textarea name="purpose" class="form-control" required>{{ old('purpose', $loan->purpose) }}</textarea>
            </div>
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="notes" class="form-control">{{ old('notes', $loan->notes) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('peminjam.loans.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection