@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header"><h4>Edit Alat: {{ $tool->name }}</h4></div>
    <div class="card-body">

        <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama Alat --}}
            <div class="form-group">
                <label>Nama Alat *</label>
                <input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $tool->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="form-group">
                <label>Kategori *</label>
                <select name="category_id"
                    class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $tool->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipe Item — sesuai ENUM: single, bundle, bundle_tool --}}
            <div class="form-group">
                <label>Tipe Item</label>
                <select name="item_type"
                    class="form-control @error('item_type') is-invalid @enderror">
                    <option value="single"      {{ old('item_type', $tool->item_type) == 'single'      ? 'selected' : '' }}>Single (Satuan)</option>
                    <option value="bundle"      {{ old('item_type', $tool->item_type) == 'bundle'      ? 'selected' : '' }}>Bundle (Paket)</option>
                    <option value="bundle_tool" {{ old('item_type', $tool->item_type) == 'bundle_tool' ? 'selected' : '' }}>Bundle Tool (Isi Paket)</option>
                </select>
                @error('item_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Harga --}}
            <div class="form-group">
                <label>Harga (Rupiah) *</label>
                <input type="number" name="price"
                    class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price', $tool->price) }}" min="0" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Minimal Credit Score --}}
            <div class="form-group">
                <label>Minimal Credit Score</label>
                <input type="number" name="min_credit_score"
                    class="form-control @error('min_credit_score') is-invalid @enderror"
                    value="{{ old('min_credit_score', $tool->min_credit_score) }}"
                    min="0" max="100">
                <small class="text-muted">User dengan credit score di bawah nilai ini tidak bisa meminjam alat ini.</small>
                @error('min_credit_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $tool->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Foto saat ini --}}
            <div class="form-group">
                <label>Foto Saat Ini</label><br>
                @if($tool->photo_path)
                    <img src="{{ asset('storage/' . $tool->photo_path) }}"
                        id="preview-img" width="100"
                        style="border-radius:6px; border:1px solid #ddd;" class="mb-1">
                    <br><small class="text-muted">Unggah foto baru untuk mengganti.</small>
                @else
                    <img src="{{ asset('images/no-image.png') }}"
                        id="preview-img" width="100"
                        style="border-radius:6px; border:1px solid #ddd;" class="mb-1">
                    <br><small class="text-muted">Belum ada foto.</small>
                @endif
            </div>

            {{-- Ganti Foto --}}
            <div class="form-group">
                <label>Ganti Foto <small class="text-muted">(kosongkan jika tidak ingin diubah)</small></label>
                <input type="file" name="photo" id="photo"
                    class="form-control-file @error('photo') is-invalid @enderror"
                    accept="image/jpg,image/jpeg,image/png">
                <small class="text-muted">Maks 2MB. Format: jpg, jpeg, png</small>
                @error('photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
    // Preview foto baru saat dipilih
    document.getElementById('photo').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview-img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection