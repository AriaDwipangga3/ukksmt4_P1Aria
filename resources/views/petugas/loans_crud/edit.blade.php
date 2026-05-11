@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header"><h4>Edit Peminjaman #{{ $loan->id }}</h4></div>
    <div class="card-body">

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('petugas.loans_crud.update', $loan->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Peminjam --}}
            <div class="form-group">
                <label>Peminjam</label>
                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                    @foreach($peminjam as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', $loan->user_id) == $user->id ? 'selected' : '' }}>
                            {{ optional($user->detail)->name ?? $user->email }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Alat --}}
            <div class="form-group">
                <label>Alat</label>
                <select name="tool_id" id="tool_id" class="form-control @error('tool_id') is-invalid @enderror" required>
                    @foreach($tools as $tool)
                        <option value="{{ $tool->id }}"
                            {{ old('tool_id', $loan->tool_id) == $tool->id ? 'selected' : '' }}>
                            {{ $tool->name }}
                        </option>
                    @endforeach
                </select>
                @error('tool_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Alat --}}
            <div class="form-group">
                <label>Unit Alat</label>
                <select name="unit_code" id="unit_code" class="form-control @error('unit_code') is-invalid @enderror" required>
                    @foreach($units as $unit)
                        <option value="{{ $unit->code }}"
                            {{ old('unit_code', $loan->unit_code) == $unit->code ? 'selected' : '' }}>
                            {{ $unit->code }} ({{ $unit->status }})
                        </option>
                    @endforeach
                </select>
                @error('unit_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="pending"   {{ old('status', $loan->status) == 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="active"    {{ old('status', $loan->status) == 'active'    ? 'selected' : '' }}>Aktif / Dipinjam</option>
                    <option value="returned"  {{ old('status', $loan->status) == 'returned'  ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="rejected"  {{ old('status', $loan->status) == 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                    <option value="cancelled" {{ old('status', $loan->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Pinjam --}}
            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="loan_date"
                    class="form-control @error('loan_date') is-invalid @enderror"
                    value="{{ old('loan_date', $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('Y-m-d') : '') }}"
                    required>
                @error('loan_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Kembali --}}
            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="due_date"
                    class="form-control @error('due_date') is-invalid @enderror"
                    value="{{ old('due_date', $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('Y-m-d') : '') }}"
                    required>
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tujuan --}}
            <div class="form-group">
                <label>Tujuan</label>
                <textarea name="purpose" rows="3"
                    class="form-control @error('purpose') is-invalid @enderror"
                    required>{{ old('purpose', $loan->purpose) }}</textarea>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catatan --}}
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="notes" rows="2"
                    class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $loan->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('petugas.loans_crud.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

{{-- Script diletakkan langsung di sini, tidak pakai @push --}}
<script>
    const currentUnitCode = "{{ old('unit_code', $loan->unit_code) }}";

    document.getElementById('tool_id').addEventListener('change', function () {
        const toolId     = this.value;
        const unitSelect = document.getElementById('unit_code');

        if (!toolId) return;

        unitSelect.innerHTML = '<option value="">Memuat...</option>';
        unitSelect.disabled  = true;

        fetch('/api/get-units/' + toolId)
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.length === 0) {
                    unitSelect.innerHTML = '<option value="">Tidak ada unit tersedia</option>';
                    unitSelect.disabled  = true;
                } else {
                    unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';
                    data.forEach(function(unit) {
                        const selected = (unit.code === currentUnitCode) ? 'selected' : '';
                        unitSelect.innerHTML += '<option value="' + unit.code + '" ' + selected + '>' + unit.code + ' (' + unit.status + ')</option>';
                    });
                    unitSelect.disabled = false;
                }
            })
            .catch(function() {
                unitSelect.innerHTML = '<option value="">Gagal memuat unit</option>';
                unitSelect.disabled  = false;
            });
    });
</script>

@endsection