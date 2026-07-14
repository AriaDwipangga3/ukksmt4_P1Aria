@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">📘 Panduan Penggunaan untuk Admin</h4>
        </div>
        <div class="card-body">
            <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Anda memiliki akses penuh sebagai Administrator. Berikut adalah fitur-fitur yang dapat Anda gunakan:</p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <h5><i class="icon-user"></i> Manajemen User</h5>
                            <p>Kelola data pengguna (admin, petugas, peminjam). Anda dapat menambah, mengedit, menghapus, dan mengubah role pengguna.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Buka menu <strong>Master Data → User</strong>, gunakan tombol <strong>Tambah User</strong>, isi form, pilih role, simpan. Untuk edit/hapus klik ikon di kolom Aksi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body">
                            <h5><i class="icon-list"></i> Manajemen Kategori & Alat</h5>
                            <p>Kelola kategori alat dan data alat (nama, harga, stok unit, foto, dll).</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Buka <strong>Master Data → Kategori/Alat</strong>. Untuk alat, pastikan Anda mengisi <strong>code_slug</strong> unik (contoh: laptop-1). Setelah alat dibuat, tambahkan unit alat (kode unit, status).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <h5><i class="icon-list"></i> Transaksi Peminjaman & Pengembalian</h5>
                            <p>Lihat semua peminjaman dan pengembalian. Anda bisa menambah, mengedit, atau menghapus data transaksi.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Menu <strong>Transaksi → Peminjaman/Pengembalian</strong>. Anda bisa langsung membuat peminjaman baru dengan status <strong>borrowed</strong> (sedang dipinjam). Pengembalian dapat diedit jika diperlukan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-info">
                        <div class="card-body">
                            <h5><i class="icon-clock"></i> Log Aktivitas</h5>
                            <p>Pantau semua aktivitas pengguna (login, logout, peminjaman, pengembalian, pelanggaran).</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Menu <strong>Log Aktivitas</strong>. Gunakan filter untuk mencari aktivitas berdasarkan user, aksi, modul, atau rentang tanggal. Anda juga dapat menghapus log (per baris atau semua).</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-info mt-3">
                <i class="icon-info"></i> <strong>Tips:</strong> Gunakan sidebar untuk navigasi. Pastikan Anda selalu logout setelah selesai untuk menjaga keamanan.
            </div>
        </div>
    </div>
</div>
@endsection