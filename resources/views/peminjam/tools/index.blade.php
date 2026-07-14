@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        @foreach($tools as $tool)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <!-- Foto -->
                <div class="card-img-top-wrapper" style="height: 200px; overflow: hidden;">
                    @if($tool->photo_path)
                        <img src="{{ asset('storage/'.$tool->photo_path) }}" 
                             class="card-img-top" 
                             style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                            <span class="text-muted">Tidak ada foto</span>
                        </div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $tool->name }}</h5>
                    <p class="card-text text-muted">{{ $tool->category->name ?? '-' }}</p>
                    
                    <!-- Informasi unit -->
                    <div class="mb-2">
                        <small class="text-success">✔ Tersedia: {{ $tool->units->where('status','available')->count() }}</small><br>
                        <small class="text-primary">↺ Dipinjam: {{ $tool->units->where('status','borrowed')->count() }}</small>
                        @if($tool->units->where('status','damaged')->count() > 0)
                            <br><small class="text-danger">✘ Rusak: {{ $tool->units->where('status','damaged')->count() }}</small>
                        @endif
                    </div>

                    <a href="{{ route('peminjam.tools.show', $tool) }}" 
                       class="btn btn-primary mt-auto {{ $tool->units->where('status','available')->count() == 0 ? 'disabled' : '' }}">
                       Detail & Pinjam
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection