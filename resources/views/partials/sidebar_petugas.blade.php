{{-- =============================================
     SIDEBAR PETUGAS
     ============================================= --}}

<li class="nav-label first">Petugas Menu</li>

{{-- Dashboard --}}
<li>
    <a href="{{ route('petugas.dashboard') }}">
        <i class="icon-speedometer"></i>
        <span class="nav-text">Dashboard</span>
    </a>
</li>

{{-- Persetujuan Peminjaman --}}
<li>
    <a href="{{ route('petugas.loans.pending') }}">
        <i class="icon-check"></i>
        <span class="nav-text">Persetujuan Peminjaman</span>
    </a>
</li>

{{-- Proses Pengembalian --}}
<li>
    <a href="{{ route('petugas.returns.pending') }}">
        <i class="icon-arrow-left"></i>
        <span class="nav-text">Proses Pengembalian</span>
    </a>
</li>

{{-- Manajemen Data (dropdown) --}}
<li>
    <a class="has-arrow" href="javascript:void(0)">
        <i class="icon-list"></i>
        <span class="nav-text">Manajemen Data</span>
    </a>
    <ul>
        <li>
            <a href="{{ route('petugas.loans_crud.index') }}">
                <span class="nav-text">CRUD Peminjaman</span>
            </a>
        </li>
        <li>
            <a href="{{ route('petugas.returns_crud.index') }}">
                <span class="nav-text">CRUD Pengembalian</span>
            </a>
        </li>
        <li>
            <a href="{{ route('petugas.violations.index') }}">
                <span class="nav-text">Pelanggaran & Denda</span>
            </a>
        </li>
    </ul>
</li>

{{-- Cetak Laporan --}}
<li>
    <a href="{{ route('petugas.reports.index') }}">
        <i class="icon-printer"></i>
        <span class="nav-text">Cetak Laporan</span>
    </a>
</li>