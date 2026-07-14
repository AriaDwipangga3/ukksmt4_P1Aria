@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">📘 Panduan Penggunaan untuk Petugas</h4>
        </div>
        <div class="card-body">
            <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Anda bertugas mengelola peminjaman dan pengembalian alat. Berikut fitur-fitur Anda:</p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body">
                            <h5><i class="icon-check"></i> Persetujuan Peminjaman</h5>
                            <p>Menyetujui atau menolak pengajuan peminjaman dari peminjam.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Buka menu <strong>Persetujuan Peminjaman</strong>. Akan tampil daftar pengajuan dengan status <strong>pending</strong>. Klik <strong>Setujui</strong> jika peminjam memenuhi syarat dan unit tersedia. Klik <strong>Tolak</strong> jika tidak. Setelah disetujui, status peminjaman menjadi <strong>borrowed</strong> dan unit alat otomatis berstatus dipinjam.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <h5><i class="icon-arrow-left"></i> Proses Pengembalian</h5>
                            <p>Memproses pengembalian alat yang diajukan oleh peminjam.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Menu <strong>Proses Pengembalian</strong>. Pilih pengembalian yang pending, cek kondisi fisik alat (baik, rusak, hilang), input denda jika ada, lalu klik Proses. Status peminjaman akan berubah menjadi <strong>returned</strong> dan unit alat tersedia kembali.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <h5><i class="icon-list"></i> Manajemen Data (CRUD)</h5>
                            <p>Kelola data peminjaman, pengembalian, dan pelanggaran secara manual.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Pada submenu <strong>Manajemen Data</strong>, Anda dapat menambah, mengedit, atau menghapus data peminjaman (misal untuk koreksi). Untuk pengembalian, Anda hanya bisa mengedit kondisi dan denda setelah diproses. Untuk pelanggaran, Anda bisa mencatat denda baru (terlambat/rusak/hilang) dan mengubah status pembayaran.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-info">
                        <div class="card-body">
                            <h5><i class="icon-printer"></i> Cetak Laporan</h5>
                            <p>Mencetak laporan peminjaman, pengembalian, atau pelanggaran.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Menu <strong>Cetak Laporan</strong>. Pilih filter tanggal dan status/kondisi, lalu klik <strong>Cetak</strong> untuk membuka halaman print preview. Anda bisa print ke PDF atau kertas.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-info mt-3">
                <i class="icon-info"></i> <strong>Tips:</strong> Pastikan Anda mengecek kondisi alat secara fisik sebelum menyetujui atau memproses pengembalian. Gunakan catatan untuk dokumentasi.
            </div>
        </div>
    </div>
</div>
@endsection