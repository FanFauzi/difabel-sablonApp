@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container-fluid">

    <!-- Product Creation Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Informasi Produk Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                            </h6>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-tag text-primary me-1"></i>Nama Produk
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Contoh: Kaos Custom Polos" required>
                                    <div class="form-text">Nama produk yang akan ditampilkan</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-bold">
                                        <i class="fas fa-folder text-primary me-1"></i>Kategori
                                    </label>
                                    <select class="form-select form-select-lg @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
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
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-align-left text-primary me-1"></i>Deskripsi Produk
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="Jelaskan detail produk, bahan, ukuran, dll.">{{ old('description') }}</textarea>
                                <div class="form-text">Deskripsi detail produk (opsional)</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing & Stock Section -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-dollar-sign me-2"></i>Harga & Stok
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-primary me-1"></i>Harga (Rp)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control form-control-lg @error('price') is-invalid @enderror"
                                               id="price_display" name="price_display" value="{{ old('price_display', old('price') ? number_format(old('price'), 0, ',', '.') : '') }}"
                                               placeholder="0" required>
                                        <input type="hidden" id="price" name="price" value="{{ old('price') }}">
                                    </div>
                                    <div class="form-text">Harga per unit produk dalam Rupiah (minimal Rp 1.000)</div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label fw-bold">
                                        <i class="fas fa-boxes text-primary me-1"></i>Stok Awal
                                    </label>
                                    <input type="number" class="form-control form-control-lg @error('stock') is-invalid @enderror"
                                           id="stock" name="stock" value="{{ old('stock', 0) }}"
                                           placeholder="0" min="0" required>
                                    <div class="form-text">Jumlah stok tersedia</div>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-image me-2"></i>Gambar Produk
                            </h6>
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">
                                    <i class="fas fa-upload text-primary me-1"></i>Upload Gambar
                                </label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB (opsional)</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Preview -->
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

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Simpan Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Tips & Panduan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-tag me-2"></i>Nama Produk
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Gunakan nama yang deskriptif</li>
                            <li>Sertakan kata kunci seperti "Custom", "Sablon"</li>
                            <li>Jaga agar tetap singkat dan jelas</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-dollar-sign me-2"></i>Harga & Stok
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Harga dalam Rupiah tanpa titik/koma</li>
                            <li>Stok 0 = produk tidak tersedia</li>
                            <li>Perbarui stok secara berkala</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-image me-2"></i>Gambar Produk
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Gunakan gambar berkualitas tinggi</li>
                            <li>Rasio gambar 1:1 untuk hasil terbaik</li>
                            <li>Background putih atau transparan</li>
                        </ul>
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Info:</strong> Produk yang sudah dibuat dapat diedit atau dihapus kapan saja dari halaman daftar produk.
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow-lg border-0 mt-3" style="border-radius: 15px;">
                <div class="card-header bg-gradient-success text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Produk
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <div class="h4 mb-1 text-primary">{{ \App\Models\Product::count() }}</div>
                            <small class="text-muted">Total Produk</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1 text-success">{{ \App\Models\Product::where('stock', '>', 0)->count() }}</div>
                            <small class="text-muted">Tersedia</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
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

// Form reset function
function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('imagePreview').classList.add('d-none');
}

// Price formatting
document.getElementById('price_display').addEventListener('input', function(e) {
    // Remove any non-numeric characters
    let value = this.value.replace(/[^\d]/g, '');

    // Format with thousand separators for display
    if (value) {
        this.value = parseInt(value).toLocaleString('id-ID');
    }

    // Update hidden input with raw numeric value
    document.getElementById('price').value = value;
});

// Initialize price formatting on page load
document.addEventListener('DOMContentLoaded', function() {
    const priceDisplay = document.getElementById('price_display');
    const priceHidden = document.getElementById('price');

    if (priceDisplay.value) {
        let value = priceDisplay.value.replace(/[^\d]/g, '');
        if (value) {
            priceDisplay.value = parseInt(value).toLocaleString('id-ID');
            priceHidden.value = value;
        }
    }
});

// Form validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    const priceDisplay = document.getElementById('price_display');
    const priceHidden = document.getElementById('price');
    const stock = document.getElementById('stock').value;

    // Get raw numeric value from display input
    const rawPrice = priceDisplay.value.replace(/[^\d]/g, '');

    // Update hidden input with raw value
    priceHidden.value = rawPrice;

    // Validate price
    if (!rawPrice || parseFloat(rawPrice) <= 0) {
        e.preventDefault();
        alert('Harga produk harus lebih dari 0!');
        priceDisplay.focus();
        return false;
    }

    // Validate stock
    if (parseInt(stock) < 0) {
        e.preventDefault();
        alert('Stok tidak boleh negatif!');
        document.getElementById('stock').focus();
        return false;
    }

    // Additional validation for minimum price
    if (parseFloat(rawPrice) < 1000) {
        e.preventDefault();
        alert('Harga produk minimal Rp 1.000!');
        priceDisplay.focus();
        return false;
    }
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
}
</style>
@endsection