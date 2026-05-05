@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Alat: {{ $tool->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Alat</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $tool->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Tipe Item</label>
                        <select name="item_type" class="form-control" required>
                            <option value="barang" {{ old('item_type', $tool->item_type) == 'barang' ? 'selected' : '' }}>Barang</option>
                            <option value="bundle" {{ old('item_type', $tool->item_type) == 'bundle' ? 'selected' : '' }}>Bundle</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga (Rupiah)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $tool->price) }}" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Minimal Credit Score</label>
                        <input type="number" name="min_credit_score" class="form-control @error('min_credit_score') is-invalid @enderror" value="{{ old('min_credit_score', $tool->min_credit_score) }}">
                        @error('min_credit_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $tool->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto Alat Saat Ini</label><br>
                        @if($tool->photo_path)
                            <img src="{{ asset('storage/' . $tool->photo_path) }}" width="100" class="img-thumbnail mb-2">
                        @else
                            <span class="badge badge-secondary">Tidak ada foto</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>Ganti Foto (opsional)</label>
                        <input type="file" name="photo" class="form-control-file" accept="image/*">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection