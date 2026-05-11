@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h4>Proses Pengembalian</h4></div>
            <div class="card-body">
                <p>Peminjam: {{ $return->loan->user->name }}</p>
                <p>Alat: {{ $return->loan->tool->name }} ({{ $return->loan->unit_code }})</p>
                <p>Tgl pengajuan: {{ $return->return_date }}</p>
                <p>Foto bukti: <a href="{{ asset('storage/'.$return->proof_photo) }}" target="_blank">Lihat</a></p>
                <form method="POST" action="{{ route('petugas.returns.process', $return) }}">
                    @csrf
                    <div class="form-group">
                        <label>Kondisi Fisik Alat</label>
                        <select name="condition" class="form-control" required>
                            <option value="good">Baik</option>
                            <option value="damaged">Rusak</option>
                            <option value="lost">Hilang</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Denda (jika ada)</label>
                        <input type="number" name="fine" class="form-control" value="0">
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
</div>
@endsection