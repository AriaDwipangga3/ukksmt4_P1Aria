@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Laporan Transaksi</h4>
        </div>
        <div class="card-body">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="reportTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="loans-tab" data-toggle="tab" href="#loans" role="tab">Laporan Peminjaman</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="returns-tab" data-toggle="tab" href="#returns" role="tab">Laporan Pengembalian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="violations-tab" data-toggle="tab" href="#violations" role="tab">Laporan Pelanggaran</a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <!-- TAB PEMINJAMAN -->
                <div class="tab-pane fade show active" id="loans" role="tabpanel">
                    <form method="GET" action="{{ route('petugas.reports.index') }}" class="row mb-3">
                        <div class="col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="loan_date_from" class="form-control" value="{{ request('loan_date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="loan_date_to" class="form-control" value="{{ request('loan_date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="loan_status" class="form-control">
                                <option value="">Semua</option>
                                <option value="pending" {{ request('loan_status')=='pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('loan_status')=='approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="borrowed" {{ request('loan_status')=='borrowed' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="returned" {{ request('loan_status')=='returned' ? 'selected' : '' }}>Dikembalikan</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                            <button type="button" class="btn btn-secondary" onclick="printReport('loans')">Cetak</button>
                        </div>
                    </form>
                    <div class="table-responsive" id="printableLoans">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr><th>ID</th><th>Peminjam</th><th>Alat</th><th>Unit</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($loans ?? [] as $loan)
                                <tr>
                                    <td>{{ $loan->id }}</td><td>{{ $loan->user->name ?? '-' }}</td><td>{{ $loan->tool->name ?? '-' }}</td><td>{{ $loan->unit_code }}</td><td>{{ $loan->loan_date }}</td><td>{{ $loan->due_date }}</td><td>{{ $loan->status }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center">Belum ada data. Silakan filter berdasarkan tanggal.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB PENGEMBALIAN -->
                <div class="tab-pane fade" id="returns" role="tabpanel">
                    <form method="GET" action="{{ route('petugas.reports.index') }}" class="row mb-3">
                        <div class="col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="return_date_from" class="form-control" value="{{ request('return_date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="return_date_to" class="form-control" value="{{ request('return_date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Kondisi</label>
                            <select name="return_condition" class="form-control">
                                <option value="">Semua</option>
                                <option value="good" {{ request('return_condition')=='good' ? 'selected' : '' }}>Baik</option>
                                <option value="damaged" {{ request('return_condition')=='damaged' ? 'selected' : '' }}>Rusak</option>
                                <option value="lost" {{ request('return_condition')=='lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                            <button type="button" class="btn btn-secondary" onclick="printReport('returns')">Cetak</button>
                        </div>
                    </form>
                    <div class="table-responsive" id="printableReturns">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr><th>ID</th><th>Peminjam</th><th>Alat</th><th>Unit</th><th>Tgl Kembali</th><th>Kondisi</th><th>Denda</th></tr>
                            </thead>
                            <tbody>
                                @forelse($returns ?? [] as $return)
                                <tr>
                                    <td>{{ $return->id }}</td><td>{{ $return->loan->user->name ?? '-' }}</td><td>{{ $return->loan->tool->name ?? '-' }}</td><td>{{ $return->loan->unit_code ?? '-' }}</td><td>{{ $return->return_date }}</td><td>{{ $return->condition }}</td><td>{{ number_format($return->fine,0,',','.') }}</td>
                                </tr>
                                @empty
                                <td><td colspan="7" class="text-center">Belum ada data. Silakan filter tanggal.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB PELANGGARAN -->
                <div class="tab-pane fade" id="violations" role="tabpanel">
                    <form method="GET" action="{{ route('petugas.reports.index') }}" class="row mb-3">
                        <div class="col-md-3">
                            <label>Dari Tanggal</label>
                            <input type="date" name="violation_date_from" class="form-control" value="{{ request('violation_date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="violation_date_to" class="form-control" value="{{ request('violation_date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tipe</label>
                            <select name="violation_type" class="form-control">
                                <option value="">Semua</option>
                                <option value="late" {{ request('violation_type')=='late' ? 'selected' : '' }}>Terlambat</option>
                                <option value="damaged" {{ request('violation_type')=='damaged' ? 'selected' : '' }}>Rusak</option>
                                <option value="lost" {{ request('violation_type')=='lost' ? 'selected' : '' }}>Hilang</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                            <button type="button" class="btn btn-secondary" onclick="printReport('violations')">Cetak</button>
                        </div>
                    </form>
                    <div class="table-responsive" id="printableViolations">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr><th>ID</th><th>Peminjam</th><th>Alat</th><th>Tipe</th><th>Denda</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @forelse($violations ?? [] as $violation)
                                <tr>
                                    <td>{{ $violation->id }}</td><td>{{ $violation->user->name ?? '-' }}</td><td>{{ $violation->loan->tool->name ?? '-' }}</td><td>{{ $violation->type }}</td><td>{{ number_format($violation->fine,0,',','.') }}</td><td>{{ $violation->status }}</td>
                                </tr>
                                @empty
                                <td><td colspan="6" class="text-center">Belum ada data. Silakan filter.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function printReport(tab) {
        let content = '';
        let title = '';
        if (tab === 'loans') {
            content = document.getElementById('printableLoans').innerHTML;
            title = 'Laporan Peminjaman';
        } else if (tab === 'returns') {
            content = document.getElementById('printableReturns').innerHTML;
            title = 'Laporan Pengembalian';
        } else {
            content = document.getElementById('printableViolations').innerHTML;
            title = 'Laporan Pelanggaran';
        }
        let printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head><title>${title}</title>
            <style>
                body { font-family: Arial, sans-serif; margin:20px; }
                table { width:100%; border-collapse: collapse; margin-top:10px; }
                th, td { border:1px solid #ccc; padding:8px; text-align:left; }
                th { background:#f2f2f2; }
                h2 { text-align:center; }
                .date { text-align:right; margin-bottom:20px; }
            </style>
            </head>
            <body>
                <h2>${title}</h2>
                <div class="date">Dicetak: ${new Date().toLocaleString()}</div>
                <div>${content}</div>
                <script>window.onload = function() { window.print(); }<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush