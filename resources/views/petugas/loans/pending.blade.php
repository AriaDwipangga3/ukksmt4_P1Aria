@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header"><h4>Pengajuan Peminjaman Menunggu Persetujuan</h4></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        <table class="table table-bordered">
            <thead>
                <tr><th>ID</th><th>Peminjam</th><th>Alat</th><th>Unit</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td>{{ $loan->id }}</td>
                    <td>{{ $loan->user->name }}<br><small>{{ $loan->user->email }}</small></td>
                    <td>{{ $loan->tool->name }}</td>
                    <td>{{ $loan->unit_code }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>{{ $loan->due_date }}</td>
                    <td>
                        <form action="{{ route('petugas.loans.approve', $loan) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui?')">Setujui</button>
                        </form>
                        <form action="{{ route('petugas.loans.reject', $loan) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak?')">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <td><td colspan="7">Tidak ada pengajuan pending</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $loans->links() }}
    </div>
</div>
@endsection