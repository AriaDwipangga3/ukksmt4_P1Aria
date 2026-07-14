<li class="nav-label first">Admin Menu</li>
<li><a href="{{ route('admin.dashboard') }}"><i class="ti-dashboard"></i> <span class="nav-text">Dashboard</span></a></li>

<li><a class="has-arrow" href="javascript:void(0)"><i class="ti-layout"></i> <span class="nav-text">Master Data</span></a>
    <ul>
        <li><a href="{{ route('admin.users.index') }}"><i class="ti-user"></i> User</a></li>
        <li><a href="{{ route('admin.tools.index') }}"><i class="ti-harddrives"></i> Alat</a></li>
        <li><a href="{{ route('admin.categories.index') }}"><i class="ti-folder"></i> Kategori</a></li>
    </ul>
</li>

<li><a class="has-arrow" href="javascript:void(0)"><i class="ti-list"></i> <span class="nav-text">Transaksi</span></a>
    <ul>
        <li><a href="{{ route('admin.loans_crud.index') }}"><i class="ti-handbag"></i> Peminjaman</a></li>
        <li><a href="{{ route('admin.returns_crud.index') }}"><i class="ti-arrow-left"></i> Pengembalian</a></li>
    </ul>
</li>

<li><a href="{{ route('admin.activity_logs.index') }}"><i class="ti-alarm-clock"></i> <span class="nav-text">Log Aktivitas</span></a></li>