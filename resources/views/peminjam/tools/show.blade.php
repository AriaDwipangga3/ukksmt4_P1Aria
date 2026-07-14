@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>{{ $tool->name }}</h4></div>
            <div class="card-body">
                <p><strong>Kategori:</strong> {{ $tool->category->name ?? '-' }}</p>
                <p><strong>Harga:</strong> Rp {{ number_format($tool->price) }}</p>
                <p><strong>Deskripsi:</strong> {{ $tool->description }}</p>
                <hr>
                <h5>Status Unit Alat</h5>
                <ul>
                    <li><span class="text-success">Tersedia:</span> {{ $tool->units->where('status','available')->count() }} unit</li>
                    <li><span class="text-primary">Sedang dipinjam:</span> {{ $tool->units->where('status','borrowed')->count() }} unit</li>
                    @php
                        $damaged = $tool->units->where('status','damaged');
                        $maintenance = $tool->units->where('status','maintenance');
                    @endphp
                    @if($damaged->count() > 0)
                        <li><span class="text-danger">Rusak:</span> {{ $damaged->count() }} unit</li>
                    @endif
                    @if($maintenance->count() > 0)
                        <li><span class="text-warning">Perawatan:</span> {{ $maintenance->count() }} unit</li>
                    @endif
                </ul>
                <p><strong>Kode unit yang tersedia:</strong> 
                    {{ $tool->units->where('status','available')->pluck('code')->implode(', ') ?: 'Tidak ada' }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Form Peminjaman</h4></div>
            <div class="card-body">
                @if($tool->units->where('status','available')->count() > 0)
                <form action="{{ route('peminjam.loans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                    <div class="form-group">
                        <label>Pilih Unit Alat</label>
                        <select name="unit_code" class="form-control" required>
                            <option value="">-- Pilih Unit --</option>
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
                @else
                <div class="alert alert-warning">⚠️ Maaf, saat ini tidak ada unit alat yang tersedia untuk dipinjam.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection