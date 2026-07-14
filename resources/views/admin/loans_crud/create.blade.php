@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Tambah Peminjaman</h4></div>
    <div class="card-body">
        <form action="{{ route('admin.loans_crud.store') }}" method="POST">
            @csrf
            {{-- Peminjam --}}
            <div class="form-group">
                <label>Peminjam</label>
                <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Peminjam --</option>
                    @foreach($peminjam as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->detail->name ?? $user->email }} ({{ $user->email }})
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
                    <option value="">-- Pilih Alat --</option>
                    @foreach($tools as $tool)
                        <option value="{{ $tool->id }}" {{ old('tool_id') == $tool->id ? 'selected' : '' }}>
                            {{ $tool->name }}
                        </option>
                    @endforeach
                </select>
                @error('tool_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit Alat (diisi dinamis via JS) --}}
            <div class="form-group">
                <label>Unit Alat</label>
                <select name="unit_code" id="unit_code" class="form-control @error('unit_code') is-invalid @enderror" required disabled>
                    <option value="">-- Pilih alat dulu --</option>
                </select>
                @error('unit_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Pinjam --}}
            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="loan_date"
                    class="form-control @error('loan_date') is-invalid @enderror"
                    value="{{ old('loan_date', date('Y-m-d')) }}" required>
                @error('loan_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal Kembali --}}
            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="due_date"
                    class="form-control @error('due_date') is-invalid @enderror"
                    value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                @error('due_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tujuan --}}
            <div class="form-group">
                <label>Tujuan</label>
                <textarea name="purpose" rows="3"
                    class="form-control @error('purpose') is-invalid @enderror"
                    required>{{ old('purpose') }}</textarea>
                @error('purpose')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catatan --}}
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="notes" rows="2"
                    class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.loans_crud.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
        </form>
    </div>
</div>
@endsection