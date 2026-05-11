@extends('layouts.app')
@section('content')

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h4>Edit Pengembalian #{{ $return->id }}</h4></div>
            <div class="card-body">

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Info peminjaman readonly --}}
                <div class="alert alert-info mb-4">
                    <strong>Info Peminjaman</strong><br>
                    Peminjam : {{ optional(optional($return->loan)->user->detail)->name ?? optional($return->loan)->user->email ?? '-' }}<br>
                    Alat     : {{ optional(optional($return->loan)->tool)->name ?? '-' }}<br>
                    Unit     : <code>{{ optional($return->loan)->unit_code ?? '-' }}</code>
                </div>

                {{-- Bukti foto lama --}}
                @if($return->proof_photo)
                    <div class="form-group">
                        <label>Bukti Foto</label><br>
                        <img src="{{ asset('storage/' . $return->proof_photo) }}"
                            width="150" style="border-radius:6px; border:1px solid #ddd;">
                    </div>
                @endif

                <form action="{{ route('petugas.returns_crud.update', $return->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Tanggal Kembali --}}
                    <div class="form-group">
                        <label>Tanggal Kembali</label>
                        <input type="date" name="return_date"
                            class="form-control @error('return_date') is-invalid @enderror"
                            value="{{ old('return_date', $return->return_date ? \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') : '') }}"
                            required>
                        @error('return_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kondisi --}}
                    <div class="form-group">
                        <label>Kondisi Alat</label>
                        <select name="condition" class="form-control @error('condition') is-invalid @enderror" required>
                            <option value="good"    {{ old('condition', $return->condition) == 'good'    ? 'selected' : '' }}>Baik</option>
                            <option value="damaged" {{ old('condition', $return->condition) == 'damaged' ? 'selected' : '' }}>Rusak</option>
                            <option value="lost"    {{ old('condition', $return->condition) == 'lost'    ? 'selected' : '' }}>Hilang</option>
                        </select>
                        @error('condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Denda --}}
                    <div class="form-group">
                        <label>Denda (Rp)</label>
                        <input type="number" name="fine"
                            class="form-control @error('fine') is-invalid @enderror"
                            value="{{ old('fine', $return->fine ?? 0) }}"
                            min="0" required>
                        @error('fine')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea name="notes" rows="3"
                            class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $return->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending"   {{ old('status', $return->status) == 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ old('status', $return->status) == 'processed' ? 'selected' : '' }}>Diproses</option>
                        </select>
                        <small class="text-muted">* Setelah diubah ke <strong>Diproses</strong>, status peminjaman otomatis menjadi <strong>Dikembalikan</strong> dan unit kembali tersedia.</small>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('petugas.returns_crud.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection