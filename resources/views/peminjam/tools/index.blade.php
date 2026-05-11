@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        @foreach($tools as $tool)
        <div class="col-md-4 mb-3">
            <div class="card">
                @if($tool->photo_path)
                <img src="{{ asset('storage/'.$tool->photo_path) }}" class="card-img-top" width="100%">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $tool->name }}</h5>
                    <p class="card-text">{{ $tool->category->name ?? '-' }}</p>
                    <p class="card-text">Stok tersedia: {{ $tool->units->where('status','available')->count() }}</p>
                    <a href="{{ route('peminjam.tools.show', $tool) }}" class="btn btn-primary">Detail & Pinjam</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection