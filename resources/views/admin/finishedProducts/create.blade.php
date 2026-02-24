@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important;">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Formulir Produk Baru
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.finishedProduct.store') }}" method="POST"
                            enctype="multipart/form-data" id="productForm">
                            @csrf

                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h6>
                                <div class="row">
                                    <div class="col-md-7 mb-3">
                                        <label for="name" class="form-label fw-bold">Nama Produk</label>
                                        <input type="text"
                                            class="form-control form-control-lg @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Contoh: Kaos Custom Desain" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="category" class="form-label fw-bold">Kategori Produk</label>
                                        <select class="form-select form-select-lg @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <option value="kaos" {{ old('category') == 'kaos' ? 'selected' : '' }}>Kaos
                                            </option>
                                            <option value="kemeja" {{ old('category') == 'kemeja' ? 'selected' : '' }}>
                                                Kemeja</option>
                                            <option value="jaket" {{ old('category') == 'jaket' ? 'selected' : '' }}>Jaket
                                            </option>
                                            <option value="hoodie" {{ old('category') == 'hoodie' ? 'selected' : '' }}>
                                                Hoodie</option>
                                            <option value="topi" {{ old('category') == 'topi' ? 'selected' : '' }}>Topi
                                            </option>
                                            <option value="tas" {{ old('category') == 'tas' ? 'selected' : '' }}>Tas
                                            </option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">Deskripsi Produk</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4" placeholder="Jelaskan detail produk, bahan, ukuran, dan keunggulannya.">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-shopping-cart me-2"></i>Penjualan
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="price_display" class="form-label fw-bold">Harga (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">Rp</span>
                                            <input type="text"
                                                class="form-control form-control-lg @error('price') is-invalid @enderror"
                                                id="price_display" placeholder="Contoh: 150.000" required>
                                            <input type="hidden" id="price" name="price"
                                                value="{{ old('price') }}">
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label fw-bold">Stok</label>
                                        <input type="number"
                                            class="form-control form-control-lg @error('stock') is-invalid @enderror"
                                            id="stock" name="stock" value="{{ old('stock', 0) }}" placeholder="0"
                                            min="0" required>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="ecommerce_link" class="form-label fw-bold">
                                        <i class="fas fa-link me-1"></i>Link E-Commerce
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="fas fa-store"></i></span>
                                        <input type="url"
                                            class="form-control form-control-lg @error('ecommerce_link') is-invalid @enderror"
                                            id="ecommerce_link" name="ecommerce_link" value="{{ old('ecommerce_link') }}"
                                            placeholder="https://tokopedia.com/toko-anda/produk-ini" required>
                                    </div>
                                    <div class="form-text">Link halaman produk di Tokopedia, Shopee, dll.</div>
                                    @error('ecommerce_link')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-image me-2"></i>Gambar Produk</h6>
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-bold">Upload Gambar Utama</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/png, image/jpeg, image/webp">
                                    <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="imagePreview" class="d-none mt-3 text-center">
                                    <img id="previewImg" src="#" alt="Preview Gambar" class="img-fluid rounded"
                                        style="max-height: 250px; border: 2px dashed #ddd; padding: 5px;" />
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                        onclick="removeImage()">
                                        <i class="fas fa-times me-1"></i> Hapus Gambar
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center pt-3 border-top">
                                <a href="{{ route('admin.finishedProduct.index') }}"
                                    class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i> Simpan Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-lg border-0" style="border-radius: 15px;">
                    <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0 !important;">
                        <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Panduan Pengisian</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-dark"><i class="fas fa-tag me-2 text-info"></i>Nama & Kategori</h6>
                            <ul class="small text-muted ps-3 mb-0">
                                <li>Gunakan nama yang jelas dan deskriptif.</li>
                                <li>Pilih kategori yang paling sesuai untuk produk.</li>
                            </ul>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-dark"><i class="fas fa-dollar-sign me-2 text-info"></i>Harga & Stok</h6>
                            <ul class="small text-muted ps-3 mb-0">
                                <li>Masukkan harga tanpa titik atau koma.</li>
                                <li>Stok `0` akan membuat produk tidak bisa dipesan.</li>
                            </ul>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <h6 class="text-dark"><i class="fas fa-link me-2 text-info"></i>Link E-Commerce</h6>
                            <ul class="small text-muted ps-3 mb-0">
                                <li>Pastikan link valid dan mengarah ke produk yang benar.</li>
                                <li>Pengguna akan diarahkan ke link ini saat mengklik produk.</li>
                            </ul>
                        </div>
                        <hr>
                        <div class="mb-0">
                            <h6 class="text-dark"><i class="fas fa-image me-2 text-info"></i>Gambar</h6>
                            <ul class="small text-muted ps-3 mb-0">
                                <li>Gunakan gambar yang cerah dan berkualitas tinggi.</li>
                                <li>Rasio 1:1 (persegi) disarankan untuk tampilan terbaik.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Produk yang disimpan dapat diubah kapan saja melalui halaman Manajemen Produk.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Fungsi Preview Gambar
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewImg.src = event.target.result;
                        imagePreview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });

            window.removeImage = function() {
                imageInput.value = '';
                previewImg.src = '#';
                imagePreview.classList.add('d-none');
            }

            // Fungsi Format Harga
            const priceDisplay = document.getElementById('price_display');
            const priceHidden = document.getElementById('price');

            // Inisialisasi saat halaman dimuat jika ada old value
            if (priceHidden.value) {
                priceDisplay.value = parseInt(priceHidden.value).toLocaleString('id-ID');
            }

            priceDisplay.addEventListener('input', function(e) {
                // Hapus karakter non-numerik
                let rawValue = this.value.replace(/[^\d]/g, '');

                // Update input tersembunyi dengan nilai mentah
                priceHidden.value = rawValue;

                // Format tampilan dengan pemisah ribuan
                if (rawValue) {
                    this.value = parseInt(rawValue).toLocaleString('id-ID');
                } else {
                    this.value = '';
                }
            });

            // Pastikan nilai mentah dikirim saat form submit
            document.getElementById('productForm').addEventListener('submit', function() {
                let rawValue = priceDisplay.value.replace(/[^\d]/g, '');
                priceHidden.value = rawValue;
            });

        });
    </script>
@endpush
