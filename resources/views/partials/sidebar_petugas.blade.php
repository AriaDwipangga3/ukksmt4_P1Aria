<li class="nav-label first">Petugas Menu</li>
<li><a href="{{ route('petugas.dashboard') }}"><i class="ti-dashboard"></i> <span class="nav-text">Dashboard</span></a></li>

<li><a href="{{ route('petugas.loans.pending') }}"><i class="ti-check-box"></i> <span class="nav-text">Persetujuan Peminjaman</span></a></li>
<li><a href="{{ route('petugas.returns.pending') }}"><i class="ti-arrow-left"></i> <span class="nav-text">Proses Pengembalian</span></a></li>

<li><a class="has-arrow" href="javascript:void(0)"><i class="ti-list"></i> <span class="nav-text">Manajemen Data</span></a>
    <ul>
        <li><a href="{{ route('petugas.loans_crud.index') }}"><i class="ti-handbag"></i> CRUD Peminjaman</a></li>
        <li><a href="{{ route('petugas.returns_crud.index') }}"><i class="ti-arrow-left"></i> CRUD Pengembalian</a></li>
        <li><a href="{{ route('petugas.violations.index') }}"><i class="ti-alert"></i> Pelanggaran & Denda</a></li>
    </ul>
</li>

<li><a href="{{ route('petugas.reports.index') }}"><i class="ti-printer"></i> <span class="nav-text">Cetak Laporan</span></a></li>