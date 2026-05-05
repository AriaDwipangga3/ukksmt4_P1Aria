@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Dashboard Peminjam</h4>
                </div>
                <div class="card-body">
                    <h5>Selamat datang, {{ Auth::user()->name }}!</h5>
                    <p>Anda dapat melakukan peminjaman alat dan melihat riwayat.</p>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">📋 Daftar Alat</h5>
                                    <p class="card-text">Lihat semua alat yang tersedia untuk dipinjam.</p>
                                    <a href="{{ route('peminjam.tools.index') }}" class="btn btn-light">Lihat Alat</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">✍️ Ajukan Peminjaman</h5>
                                    <p class="card-text">Pinjam alat yang tersedia.</p>
                                    <a href="{{ route('peminjam.loans.create') }}" class="btn btn-light">Ajukan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">📜 Riwayat Peminjaman</h5>
                                    <p class="card-text">Cek status peminjaman Anda.</p>
                                    <a href="{{ route('peminjam.loans.index') }}" class="btn btn-light">Lihat Riwayat</a>
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