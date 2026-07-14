@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Denda & Tagihan Saya</h4>
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
                            <th>Alat</th>
                            <th>Unit</th>
                            <th>Jenis</th>
                            <th>Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $index => $v)
                        <tr>
                            <td>{{ $loop->iteration + ($violations->currentPage() - 1) * $violations->perPage() }}</td>
                            <td>
                                {{ $v->loan->tool->name ?? '-' }}
                                <br><small class="text-muted">{{ $v->loan->tool->code_slug ?? '' }}</small>
                            </td>
                            <td><code>{{ $v->loan->unit_code ?? '-' }}</code></td>
                            <td>
                                @if($v->type == 'late') Terlambat
                                @elseif($v->type == 'damaged') Rusak
                                @elseif($v->type == 'lost') Hilang
                                @else - @endif
                            </td>
                            <td><strong>Rp {{ number_format($v->fine, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($v->status == 'unpaid')
                                    <span class="badge badge-danger">Belum bayar</span>
                                @else
                                    <span class="badge badge-success">Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="ti-folder"></i> Tidak ada denda atau tagihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($violations->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $violations->firstItem() ?? 0 }} - {{ $violations->lastItem() ?? 0 }}
                        dari {{ $violations->total() }} data
                    </small>
                    {{ $violations->appends(request()->query())->links() }}
                </div>
            @endif

            <div class="alert alert-info mt-3">
                <i class="icon-info"></i> <strong>Informasi Pembayaran Denda:</strong> 
                Pembayaran denda hanya dapat dilakukan melalui petugas. Silakan hubungi petugas untuk melunasi denda yang tertera.
            </div>
        </div>
    </div>
</div>
@endsection