@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Riwayat Pengembalian Saya</h4></div>
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Alat</th><th>Tgl Pengembalian</th><th>Status</th><th>Denda</th></tr></thead>
            <tbody>
                @foreach($returns as $return)
                <tr>
                    <td>{{ $return->loan->tool->name ?? '-' }}</td>
                    <td>{{ $return->return_date }}</td>
                    <td>{{ ucfirst($return->status) }}</td>
                    <td>Rp {{ number_format($return->fine) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $returns->links() }}
    </div>
</div>
@endsection