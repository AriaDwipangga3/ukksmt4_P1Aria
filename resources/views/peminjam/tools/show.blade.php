@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>{{ $tool->name }}</h4></div>
            <div class="card-body">
                <p>Kategori: {{ $tool->category->name ?? '-' }}</p>
                <p>Harga: Rp {{ number_format($tool->price) }}</p>
                <p>Deskripsi: {{ $tool->description }}</p>
                <p>Unit tersedia: 
                    @php
                        $availableUnits = $tool->units->where('status','available')->pluck('code')->implode(', ');
                    @endphp
                    {{ $availableUnits ?: 'Tidak ada unit tersedia' }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Form Peminjaman</h4></div>
            <div class="card-body">
                <form action="{{ route('peminjam.loans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                    <div class="form-group">
                        <label>Pilih Unit Alat</label>
                        <select name="unit_code" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($tool->units->where('status','available') as $unit)
                                <option value="{{ $unit->code }}">{{ $unit->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pinjam</label>
                        <input type="date" name="loan_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Kembali</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tujuan Peminjaman</label>
                        <textarea name="purpose" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Catatan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection