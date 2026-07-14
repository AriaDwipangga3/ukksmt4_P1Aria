@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h4>Proses Pengembalian #{{ $return->id }}</h4></div>
        <div class="card-body">
            <p>Peminjam: {{ $return->loan->user->name }}</p>
            <p>Alat: {{ $return->loan->tool->name }} ({{ $return->loan->unit_code }})</p>
            <p>Tanggal pengajuan: {{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</p>
            <p><a href="{{ asset('storage/'.$return->proof_photo) }}" target="_blank">Lihat Bukti</a></p>
            <form method="POST" action="{{ route('petugas.returns.process', $return) }}">
                @csrf
                <div class="form-group">
                    <label>Kondisi Alat</label>
                    <select name="condition" class="form-control" required>
                        <option value="good">Baik</option>
                        <option value="damaged">Rusak</option>
                        <option value="lost">Hilang</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Denda (Rp)</label>
                    <input type="number" name="fine" class="form-control" value="0" min="0">
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Proses</button>
            </form>
        </div>
    </div>
</div>
@endsection