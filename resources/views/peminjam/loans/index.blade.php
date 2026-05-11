@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Riwayat Peminjaman Saya</h4></div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr><th>Alat</th><th>Unit</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                <tr>
                    <td>{{ $loan->tool->name }}</td>
                    <td>{{ $loan->unit_code }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>{{ $loan->due_date }}</td>
                    <td>
                        @if($loan->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($loan->status == 'borrowed')
                            <span class="badge badge-primary">Dipinjam</span>
                        @elseif($loan->status == 'returned')
                            <span class="badge badge-success">Dikembalikan</span>
                        @elseif($loan->status == 'rejected')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-secondary">{{ $loan->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($loan->status == 'pending')
                            <a href="{{ route('peminjam.loans.edit', $loan) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('peminjam.loans.destroy', $loan) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan pengajuan?')">Hapus</button>
                            </form>
                        @elseif($loan->status == 'borrowed')
                            @php
                                $returnExists = \App\Models\AssetReturn::where('loan_id', $loan->id)->exists();
                            @endphp
                            @if(!$returnExists)
                                <a href="{{ route('peminjam.loans.return.create', $loan) }}" class="btn btn-sm btn-info">Ajukan Pengembalian</a>
                            @else
                                <span class="badge badge-warning">Pengembalian diajukan</span>
                            @endif
                        @elseif($loan->status == 'returned')
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $loans->links() }}
    </div>
</div>
@endsection