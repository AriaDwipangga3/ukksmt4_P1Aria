@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Data Pengembalian</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Tgl Pengembalian</th>
                            <th>Kondisi</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr>
                            <td>{{ $return->id }}</td>
                            <td>
                                {{ $return->loan->user->name ?? '-' }}
                                <br><small>{{ $return->loan->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $return->loan->tool->name ?? '-' }}</td>
                            <td>{{ $return->loan->unit_code ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d/m/Y') }}</td>
                            <td>
                                @if($return->condition == 'good') Baik
                                @elseif($return->condition == 'damaged') Rusak
                                @elseif($return->condition == 'lost') Hilang
                                @else - @endif
                            </td>
                            <td>Rp {{ number_format($return->fine, 0, ',', '.') }}</td>
                            <td>
                                @if($return->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-success">Diproses</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('petugas.returns_crud.edit', $return->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('petugas.returns_crud.destroy', $return->id) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data pengembalian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $returns->links() }}
        </div>
    </div>
</div>
@endsection