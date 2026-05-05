<li class="nav-label first">Petugas Menu</li>
<li><a href="{{ route('petugas.dashboard') }}"><i class="icon-speedometer"></i><span class="nav-text">Dashboard</span></a></li>

<li><a href="{{ route('petugas.loans.pending') }}"><i class="icon-check"></i><span class="nav-text">Persetujuan Peminjaman</span></a></li>
<li><a href="{{ route('petugas.returns.pending') }}"><i class="icon-arrow-left"></i><span class="nav-text">Proses Pengembalian</span></a></li>

<li><a class="has-arrow" href="javascript:void()"><i class="icon-list"></i><span class="nav-text">Manajemen Data</span></a>
    <ul>
        <li><a href="{{ route('petugas.loans.index') }}">CRUD Peminjaman</a></li>
        <li><a href="{{ route('petugas.returns.index') }}">CRUD Pengembalian</a></li>
        <li><a href="{{ route('petugas.violations.index') }}">Pelanggaran & Denda</a></li>
    </ul>
</li>

<li><a href="{{ route('petugas.reports.index') }}"><i class="icon-printer"></i><span class="nav-text">Cetak Laporan</span></a></li>