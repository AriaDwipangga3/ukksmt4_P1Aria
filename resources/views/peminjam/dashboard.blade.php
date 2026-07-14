@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">📘 Panduan Penggunaan untuk Peminjam</h4>
        </div>
        <div class="card-body">
            <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>! Anda dapat meminjam alat dan memantau peminjaman Anda. Berikut fitur-fitur yang tersedia:</p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <h5><i class="icon-list"></i> Lihat Daftar Alat</h5>
                            <p>Menampilkan semua alat yang tersedia untuk dipinjam.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Klik menu <strong>Lihat Alat</strong>. Anda akan melihat kartu alat dengan informasi nama, kategori, harga, dan jumlah unit tersedia. Klik <strong>Detail & Pinjam</strong> untuk melanjutkan peminjaman.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body">
                            <h5><i class="icon-handbag"></i> Ajukan Peminjaman</h5>
                            <p>Mengajukan peminjaman alat yang dipilih.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Pada halaman detail alat, pilih unit yang tersedia, isi tanggal pinjam dan tanggal kembali, tujuan peminjaman, lalu klik <strong>Ajukan</strong>. Pengajuan akan masuk ke petugas untuk disetujui. Anda dapat melihat status di <strong>Riwayat Peminjaman</strong>.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <h5><i class="icon-clock"></i> Riwayat Peminjaman</h5>
                            <p>Memantau status peminjaman yang sedang berlangsung atau sudah selesai.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Menu <strong>Riwayat Peminjaman</strong> menampilkan daftar peminjaman Anda. Untuk pengajuan yang masih <strong>pending</strong>, Anda dapat mengedit atau membatalkan. Jika status sudah <strong>borrowed</strong> (disetujui), akan muncul tombol <strong>Ajukan Pengembalian</strong>.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-danger">
                        <div class="card-body">
                            <h5><i class="icon-arrow-left"></i> Ajukan Pengembalian</h5>
                            <p>Mengembalikan alat yang sedang dipinjam.</p>
                            <p class="text-muted small">📌 <strong>Tutorial:</strong> Pada riwayat peminjaman yang berstatus <strong>borrowed</strong>, klik <strong>Ajukan Pengembalian</strong>. Isi tanggal pengembalian (biasanya hari ini), upload foto bukti, tambahkan catatan jika perlu, lalu kirim. Petugas akan memproses dan menentukan denda (jika ada).</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="alert alert-info mt-3">
                <i class="icon-info"></i> <strong>Tips:</strong> Pastikan Anda mengembalikan alat tepat waktu untuk menghindari denda. Jika ada kendala, segera hubungi petugas.
            </div>
        </div>
    </div>
</div>
@endsection