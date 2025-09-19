@extends('layouts.admin')

@section('title', 'Edit Produk Kustom - ' . $product->name)

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Produk Kustom</h2>
            <p class="text-muted">Perbarui informasi untuk produk: <strong>{{ $product->name }}</strong></p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0 !important;">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Formulir Edit Produk
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                        @csrf
                        @method('PUT')

                        {{-- Ganti semua $products menjadi $product di bawah ini --}}

                        <div class="mb-4">
                            <h6 class="text-info mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="name" class="form-label fw-bold"><i class="fas fa-tag text-info me-1"></i>Nama Produk</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-bold"><i class="fas fa-list text-info me-1"></i>Kategori</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="kaos" {{ old('category', $product->category) == 'kaos' ? 'selected' : '' }}>Kaos</option>
                                        <option value="kemeja" {{ old('category', $product->category) == 'kemeja' ? 'selected' : '' }}>Kemeja</option>
                                        <option value="jaket" {{ old('category', $product->category) == 'jaket' ? 'selected' : '' }}>Jaket</option>
                                        <option value="topi" {{ old('category', $product->category) == 'topi' ? 'selected' : '' }}>Topi</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label fw-bold"><i class="fas fa-file-alt text-info me-1"></i>Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info mb-3"><i class="fas fa-dollar-sign me-2"></i>Harga & Stok</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-bold"><i class="fas fa-money-bill-wave text-info me-1"></i>Harga (Rp)</label>
                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required min="1000">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-info mb-3"><i class="fas fa-paint-roller me-2"></i>Biaya Desain</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="small_design_cost" class="form-label fw-bold"><i class="fas fa-paint-brush text-info me-1"></i>Ukuran Kecil (Rp)</label>
                                    <input type="number" class="form-control" id="small_design_cost" name="small_design_cost" value="{{ old('small_design_cost', $product->small_design_cost) }}" min="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="medium_design_cost" class="form-label fw-bold"><i class="fas fa-paint-brush text-info me-1"></i>Ukuran Sedang (Rp)</label>
                                    <input type="number" class="form-control" id="medium_design_cost" name="medium_design_cost" value="{{ old('medium_design_cost', $product->medium_design_cost) }}" min="0">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="large_design_cost" class="form-label fw-bold"><i class="fas fa-paint-brush text-info me-1"></i>Ukuran Besar (Rp)</label>
                                    <input type="number" class="form-control" id="large_design_cost" name="large_design_cost" value="{{ old('large_design_cost', $product->large_design_cost) }}" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info mb-3"><i class="fas fa-image me-2"></i>Gambar Produk</h6>
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">Pilih Gambar Baru (Opsional)</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                            </div>
                            @if($product->image)
                                <div class="mt-3">
                                    <p class="fw-bold">Gambar Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail rounded" style="max-width: 250px;">
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="fas fa-save me-2"></i>Perbarui Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection