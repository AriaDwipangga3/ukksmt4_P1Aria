<li class="nav-label first">Admin Menu</li>
<li><a href="{{ route('admin.dashboard') }}"><i class="icon-speedometer"></i><span class="nav-text">Dashboard</span></a></li>

<li><a class="has-arrow" href="javascript:void()"><i class="icon-single-04"></i><span class="nav-text">Master Data</span></a>
    <ul>
        <li><a href="{{ route('admin.users.index') }}">User</a></li>
        <li><a href="{{ route('admin.tools.index') }}">Alat</a></li>
        <li><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
    </ul>
</li>

<li><a class="has-arrow" href="javascript:void()"><i class="icon-list"></i><span class="nav-text">Transaksi</span></a>
    <ul>
        <li><a href="#">Peminjaman</a></li>
        <li><a href="#">Pengembalian</a></li>
    </ul>
</li>

<li><a href="#"><i class="icon-clock"></i><span class="nav-text">Log Aktivitas</span></a></li>