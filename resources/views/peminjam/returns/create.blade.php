@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Ajukan Pengembalian</h4></div>
            <div class="card-body">
                <p>Peminjaman: {{ $loan->tool->name }} ({{ $loan->unit_code }})</p>
                <p>Tanggal pinjam: {{ $loan->loan_date }}</p>
                <p>Jatuh tempo: {{ $loan->due_date }}</p>
                <form action="{{ route('peminjam.loans.return.store', $loan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Tanggal Pengembalian</label>
                        <input type="date" name="return_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Foto Bukti (wajib)</label>
                        <input type="file" name="proof_photo" class="form-control-file" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan (opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pengembalian</button>
                    <a href="{{ route('peminjam.loans.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection