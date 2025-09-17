@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Informasi Produk Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        
                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="name" class="form-label fw-bold"><i class="fas fa-tag text-primary me-1"></i>Nama Produk</label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Kaos Custom Polos" required>
                                    <div class="form-text">Nama produk yang akan ditampilkan</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-bold"><i class="fas fa-folder text-primary me-1"></i>Kategori</label>
                                    <select class="form-select form-select-lg @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="kaos" {{ old('category') === 'kaos' ? 'selected' : '' }}>Kaos</option>
                                        <option value="kemeja" {{ old('category') === 'kemeja' ? 'selected' : '' }}>Kemeja</option>
                                        <option value="jaket" {{ old('category') === 'jaket' ? 'selected' : '' }}>Jaket</option>
                                        <option value="topi" {{ old('category') === 'topi' ? 'selected' : '' }}>Topi</option>
                                        <option value="tas" {{ old('category') === 'tas' ? 'selected' : '' }}>Tas</option>
                                    </select>
                                    <div class="form-text">Kategori produk sablon</div>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold"><i class="fas fa-align-left text-primary me-1"></i>Deskripsi Produk</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Jelaskan detail produk, bahan, ukuran, dll.">{{ old('description') }}</textarea>
                                <div class="form-text">Deskripsi detail produk (opsional)</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-dollar-sign me-2"></i>Harga & Stok</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-bold"><i class="fas fa-money-bill text-primary me-1"></i>Harga (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control form-control-lg @error('price') is-invalid @enderror" id="price_display" value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" placeholder="0" required>
                                        <input type="hidden" id="price" name="price" value="{{ old('price') }}">
                                    </div>
                                    <div class="form-text">Harga per unit produk dalam Rupiah</div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label fw-bold"><i class="fas fa-boxes text-primary me-1"></i>Stok Awal</label>
                                    <input type="number" class="form-control form-control-lg @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" placeholder="0" min="0" required>
                                    <div class="form-text">Jumlah stok tersedia</div>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-paint-brush me-2"></i>Biaya Desain Sablon</h6>
                            <div class="form-text mb-3">Tentukan biaya tambahan untuk desain sablon kustom. Biaya ini akan ditambahkan ke total harga pesanan.</div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="small_design_cost" class="form-label fw-bold"><i class="fas fa-ruler-combined me-1"></i>Small (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control @error('small_design_cost') is-invalid @enderror" id="small_design_cost_display" value="{{ old('small_design_cost') ? number_format(old('small_design_cost'), 0, ',', '.') : '0' }}" placeholder="0" required>
                                        <input type="hidden" id="small_design_cost" name="small_design_cost" value="{{ old('small_design_cost', 0) }}">
                                    </div>
                                    @error('small_design_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="medium_design_cost" class="form-label fw-bold"><i class="fas fa-ruler-combined me-1"></i>Medium (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control @error('medium_design_cost') is-invalid @enderror" id="medium_design_cost_display" value="{{ old('medium_design_cost') ? number_format(old('medium_design_cost'), 0, ',', '.') : '0' }}" placeholder="0" required>
                                        <input type="hidden" id="medium_design_cost" name="medium_design_cost" value="{{ old('medium_design_cost', 0) }}">
                                    </div>
                                    @error('medium_design_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="large_design_cost" class="form-label fw-bold"><i class="fas fa-ruler-combined me-1"></i>Large (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control @error('large_design_cost') is-invalid @enderror" id="large_design_cost_display" value="{{ old('large_design_cost') ? number_format(old('large_design_cost'), 0, ',', '.') : '0' }}" placeholder="0" required>
                                        <input type="hidden" id="large_design_cost" name="large_design_cost" value="{{ old('large_design_cost', 0) }}">
                                    </div>
                                    @error('large_design_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-image me-2"></i>Gambar Produk</h6>
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold"><i class="fas fa-upload text-primary me-1"></i>Upload Gambar</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div id="imagePreview" class="d-none">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImage()">
                                            <i class="fas fa-times me-1"></i>Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center pt-3 border-top">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Tips & Panduan</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Info:</strong> Produk yang sudah dibuat dapat diedit atau dihapus.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = parseInt(value).toLocaleString('id-ID');
        } else {
            input.value = 0;
        }
        document.getElementById(input.id.replace('_display', '')).value = value;
    }

    document.getElementById('price_display').addEventListener('input', function() {
        formatCurrency(this);
    });
    document.getElementById('small_design_cost_display').addEventListener('input', function() {
        formatCurrency(this);
    });
    document.getElementById('medium_design_cost_display').addEventListener('input', function() {
        formatCurrency(this);
    });
    document.getElementById('large_design_cost_display').addEventListener('input', function() {
        formatCurrency(this);
    });
    
    // Image preview and removal
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('imagePreview').classList.add('d-none');
    }
</script>
@endsection