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
                    <p>Anda dapat mengelola peminjaman dan pengembalian alat.</p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title
@endsection