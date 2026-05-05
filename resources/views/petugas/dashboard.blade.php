@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Petugas</h4>
                </div>
                <div class="card-body">
                    <h5>Selamat datang, {{ Auth::user()->name }}!</h5>
                    <p>Anda dapat mengelola peminjaman, pengembalian, dan pelanggaran.</p>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">✅ Persetujuan Peminjaman</h5>
                                    <p class="card-text">Setujui atau tolak pengajuan peminjaman.</p>
                                    <a href="{{ route('petugas.loans.pending') }}" class="btn btn-light">Proses</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-secondary">
                                <div class="card-body">
                                    <h5 class="card-title">🔄 Pengembalian Alat</h5>
                                    <p class="card-text">Proses pengembalian alat dari peminjam.</p>
                                    <a href="{{ route('petugas.returns.pending') }}" class="btn btn-light">Proses</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-dark">
                                <div class="card-body">
                                    <h5 class="card-title">📊 Laporan</h5>
                                    <p class="card-text">Cetak laporan peminjaman dan pengembalian.</p>
                                    <a href="{{ route('petugas.reports.index') }}" class="btn btn-light">Cetak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="card text-white bg-danger">
                                <div class="card-body">
                                    <h5 class="card-title">⚠️ Pelanggaran & Denda</h5>
                                    <p class="card-text">Kelola pelanggaran, denda, dan sanksi.</p>
                                    <a href="{{ route('petugas.violations.index') }}" class="btn btn-light">Kelola</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">📦 CRUD Peminjaman</h5>
                                    <p class="card-text">Manajemen data peminjaman (tambah, edit, hapus).</p>
                                    <a href="{{ route('petugas.loans.index') }}" class="btn btn-light">Kelola</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">🔄 CRUD Pengembalian</h5>
                                    <p class="card-text">Manajemen data pengembalian.</p>
                                    <a href="{{ route('petugas.returns.index') }}" class="btn btn-light">Kelola</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection