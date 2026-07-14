@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Pengembalian Menunggu Proses</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Tgl Pengembalian</th>
                            <th>Foto Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $index => $r)
                        <tr>
                            <td>{{ $loop->iteration + ($returns->currentPage() - 1) * $returns->perPage() }}</td>
                            <td>
                                {{ $r->loan->user->name }}
                                <br><small class="text-muted">{{ $r->loan->user->email }}</small>
                            </td>
                            <td>
                                {{ $r->loan->tool->name }}
                                <br><small class="text-muted">{{ $r->loan->tool->code_slug }}</small>
                            </td>
                            <td><code>{{ $r->loan->unit_code }}</code></td>
                            <td>{{ \Carbon\Carbon::parse($r->return_date)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ asset('storage/'.$r->proof_photo) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="ti-image"></i> Lihat
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('petugas.returns.process.form', $r) }}" class="btn btn-sm btn-primary">
                                    <i class="ti-settings"></i> Proses
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ti-folder"></i> Tidak ada pengembalian yang menunggu proses.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returns->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $returns->firstItem() }} - {{ $returns->lastItem() }}
                        dari {{ $returns->total() }} data
                    </small>
                    {{ $returns->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection