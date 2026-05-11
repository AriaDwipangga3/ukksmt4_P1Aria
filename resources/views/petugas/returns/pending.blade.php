@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Pengembalian Menunggu Proses</h4></div>
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Peminjam</th><th>Alat</th><th>Unit</th><th>Tgl Pengembalian</th><th>Foto</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($returns as $r)
                <tr>
                    <td>{{ $r->loan->user->name }}</td>
                    <td>{{ $r->loan->tool->name }}</td>
                    <td>{{ $r->loan->unit_code }}</td>
                    <td>{{ $r->return_date }}</td>
                    <td><a href="{{ asset('storage/'.$r->proof_photo) }}" target="_blank">Lihat</a></td>
                    <td><a href="{{ route('petugas.returns.process.form', $r) }}" class="btn btn-sm btn-primary">Proses</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $returns->links() }}
    </div>
</div>
@endsection