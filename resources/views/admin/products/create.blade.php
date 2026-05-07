{{-- admin/product/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Produk Kustom Baru')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <div class="card-header bg-gradient-primary text-white"
                        style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-plus me-2"></i>Informasi Produk Kustom Baru
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                            id="productForm">
                            @csrf

<div class="mb-4">
    <h6 class="text-primary mb-3">
        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
    </h6>
    <div class="row">
        {{-- Nama Produk --}}
        <div class="col-md-8 mb-3">
            <label for="name" class="form-label fw-bold">Nama Produk</label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Kaos Custom Polos" required>
        </div>

        {{-- Kategori --}}
        <div class="col-md-4 mb-3">
            <label for="category" class="form-label fw-bold">Kategori</label>
            <select class="form-select form-select-lg @error('category') is-invalid @enderror" id="category" name="category" required>
                <option value="">Pilih Kategori</option>
                <option value="kaos" {{ old('category') === 'kaos' ? 'selected' : '' }}>Kaos</option>
                <option value="kemeja" {{ old('category') === 'kemeja' ? 'selected' : '' }}>Kemeja</option>
            </select>
        </div>
    </div>

    <div class="row">
        {{-- Input Harga Dasar (PENTING) --}}
        <div class="col-md-6 mb-3">
            <label for="price_display" class="form-label fw-bold">Harga Dasar Produk</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control form-control-lg" id="price_display" placeholder="50.000" required>
                {{-- Input tersembunyi untuk menyimpan angka murni (tanpa titik) --}}
                <input type="hidden" name="price" id="price" value="{{ old('price') }}">
            </div>
        </div>

        {{-- Input Stok (PENTING) --}}
        <div class="col-md-6 mb-3">
            <label for="stock" class="form-label fw-bold">Stok Awal</label>
            <input type="number" class="form-control form-control-lg @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
        </div>
    </div>
</div>

                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Biaya Stiker Sablon DTF
                                        (Berdasarkan Ukuran)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="small_design_cost" class="form-label fw-bold">Biaya Sablon A5 <span
                                                    class="text-muted small">(Max 15x20 cm)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="small_design_cost"
                                                    name="small_design_cost" placeholder="Contoh: 15000"
                                                    value="{{ old('small_design_cost') }}" required>
                                            </div>
                                            <div class="form-text">Cocok untuk logo saku / dada kiri.</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="medium_design_cost" class="form-label fw-bold">Biaya Sablon A4 <span
                                                    class="text-muted small">(Max 21x29 cm)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="medium_design_cost"
                                                    name="medium_design_cost" placeholder="Contoh: 30000"
                                                    value="{{ old('medium_design_cost') }}" required>
                                            </div>
                                            <div class="form-text">Cocok untuk desain standar di tengah dada.</div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="large_design_cost" class="form-label fw-bold">Biaya Sablon A3 <span
                                                    class="text-muted small">(Max 29x42 cm)</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="large_design_cost"
                                                    name="large_design_cost" placeholder="Contoh: 50000"
                                                    value="{{ old('large_design_cost') }}" required>
                                            </div>
                                            <div class="form-text">Cocok untuk desain full menutupi kaos.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-image me-2"></i>Gambar Produk
                                </h6>
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-bold">
                                        <i class="fas fa-upload text-primary me-1"></i>Upload Gambar Produk Dasar
                                    </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*">
                                    <div class="form-text">Gambar ini akan menjadi kanvas untuk desain kustom. Format: JPG,
                                        PNG, GIF. Maksimal 2MB</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="imagePreview" class="d-none">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded"
                                                style="max-height: 200px;">
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                                onclick="removeImage()">
                                                <i class="fas fa-times me-1"></i>Hapus Gambar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" onclick="resetForm()">
                                        <i class="fas fa-undo me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Simpan Produk Kustom
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <div class="card-header bg-gradient-info text-white"
                        style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
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
                                <li>Harga Dasar adalah harga produk polos sebelum desain</li>
                                {{-- Penambahan Tips Stok --}}
                                <li>Pastikan jumlah stok sesuai dengan ketersediaan barang</li>
                                {{-- Akhir Penambahan --}}
                                <li>Biaya desain akan ditambahkan secara otomatis di halaman pemesanan</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-primary">
                                <i class="fas fa-image me-2"></i>Gambar Produk
                            </h6>
                            <ul class="small text-muted mb-0">
                                <li>Gunakan gambar produk dasar (polos) berkualitas tinggi</li>
                                <li>Rasio gambar 1:1 untuk hasil terbaik</li>
                                <li>Background putih atau transparan</li>
                            </ul>
                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Info:</strong> Setelah produk ini dibuat, pelanggan dapat mulai membuat desain kustom
                            mereka sendiri di halaman pemesanan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT TETAP SAMA --}}
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
        const priceInputs = ['price_display', 'small_design_cost', 'medium_design_cost', 'large_design_cost'];

        priceInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function(e) {
                    let value = this.value.replace(/[^\d]/g, '');
                    if (id === 'price_display') {
                        document.getElementById('price').value = value;
                    }
                    if (value) {
                        this.value = parseInt(value).toLocaleString('id-ID');
                    }
                });

                // Initialize formatting on page load
                document.addEventListener('DOMContentLoaded', function() {
                    let value = input.value.replace(/[^\d]/g, '');
                    if (id === 'price_display') {
                        document.getElementById('price').value = value;
                    }
                    if (value) {
                        input.value = parseInt(value).toLocaleString('id-ID');
                    }
                });
            }
        });


        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const priceDisplay = document.getElementById('price_display');
            const priceHidden = document.getElementById('price');

            // Get raw numeric value from display input
            const rawPrice = priceDisplay.value.replace(/[^\d]/g, '');

            // Update hidden input with raw value
            priceHidden.value = rawPrice;

            // Validate price
            if (!rawPrice || parseFloat(rawPrice) < 1000) {
                e.preventDefault();
                alert('Harga dasar produk minimal Rp 1.000!');
                priceDisplay.focus();
                return false;
            }

            // Unformat design cost inputs before submit
            ['small_design_cost', 'medium_design_cost', 'large_design_cost'].forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.value = input.value.replace(/[^\d]/g, '') || '0';
                }
            });
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .form-control:focus,
        .form-select:focus {
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
