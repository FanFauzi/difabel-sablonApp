@extends('layouts.user')

@section('title', 'Buat Pesanan - ' . $product->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.products') }}">Produk</a></li>
                <li class="breadcrumb-item active">Buat Pesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Product Information -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Informasi Produk</h5>
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded mb-3"
                         style="max-height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px;">
                        <i class="fas fa-tshirt fa-3x text-muted"></i>
                    </div>
                @endif

                <h4 class="mb-2">{{ $product->name }}</h4>

                @if($product->category)
                    <span class="badge bg-primary mb-3">{{ $product->category }}</span>
                @endif

                <div class="mb-3">
                    <h3 class="text-success mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                    <small class="text-muted">per unit</small>
                </div>

                <div class="mb-3">
                    <span class="badge bg-info">Stok: {{ $product->stock }}</span>
                </div>

                @if($product->description)
                    <p class="text-muted small">{{ $product->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Pemesanan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden Product ID -->
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Step 1: Pilih Produk Sablon -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-check-circle me-2"></i>1. Produk Dipilih
                        </h6>
                        <div class="alert alert-success">
                            <strong>{{ $product->name }}</strong> - Produk sablon custom telah dipilih
                        </div>
                    </div>

                    <!-- Step 2: Masukkan Jumlah -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-list-ol me-2"></i>2. Masukkan Jumlah Pesanan
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Jumlah Pesanan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" name="quantity"
                                           value="1" min="1" max="{{ $product->stock }}" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                                </div>
                                <div class="form-text">Maksimal {{ $product->stock }} unit</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Harga</label>
                                <div class="h4 text-success" id="totalPrice">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Upload Desain (Optional) -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-upload me-2"></i>3. Upload Desain Sablon (Opsional)
                        </h6>
                        <div class="mb-3">
                            <label for="design_file" class="form-label">File Desain</label>
                            <input type="file" class="form-control" id="design_file" name="design_file"
                                   accept="image/*,.pdf,.doc,.docx">
                            <div class="form-text">
                                Format yang didukung: JPG, PNG, PDF, DOC, DOCX. Maksimal 5MB.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="design_description" class="form-label">Deskripsi Desain</label>
                            <textarea class="form-control" id="design_description" name="design_description"
                                      rows="3" placeholder="Jelaskan detail desain sablon yang Anda inginkan..."></textarea>
                            <div class="form-text">
                                Berikan detail warna, ukuran, posisi, dan instruksi khusus untuk desain Anda.
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Catatan Tambahan -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-sticky-note me-2"></i>4. Catatan Tambahan (Opsional)
                        </h6>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan Khusus</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"
                                      placeholder="Catatan tambahan untuk pesanan ini..."></textarea>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Ringkasan Pesanan</h6>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Produk:</strong> {{ $product->name }}</p>
                                    <p class="mb-1"><strong>Harga per Unit:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="mb-1"><strong>Jumlah:</strong> <span id="summaryQuantity">1</span> unit</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Subtotal:</strong> <span id="summarySubtotal">Rp {{ number_format($product->price, 0, ',', '.') }}</span></p>
                                    <p class="mb-1"><strong>Biaya Admin:</strong> Rp 0</p>
                                    <p class="mb-0"><strong>Total:</strong> <span id="summaryTotal" class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> pemesanan
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.products') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Produk
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check me-2"></i>Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Syarat dan Ketentuan Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Proses Pemesanan</h6>
                <p>Pesanan akan diproses dalam 1-2 hari kerja setelah konfirmasi pembayaran.</p>

                <h6>2. Pembayaran</h6>
                <p>Pembayaran dilakukan setelah pesanan dikonfirmasi oleh admin via COD (Cash on Delivery).</p>

                <h6>3. Desain Kustom</h6>
                <p>Desain yang diupload akan diproses sesuai dengan spesifikasi yang diberikan.</p>

                <h6>4. Pengiriman</h6>
                <p>Pengiriman dilakukan setelah pesanan selesai diproduksi.</p>

                <h6>5. Kebijakan Pembatalan</h6>
                <p>Pembatalan dapat dilakukan sebelum pesanan diproses.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    let value = parseInt(input.value);
    if (value < max) {
        input.value = value + 1;
        updateTotal();
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    let value = parseInt(input.value);
    if (value > 1) {
        input.value = value - 1;
        updateTotal();
    }
}

function updateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const price = {{ $product->price }};
    const total = quantity * price;

    // Update total price display
    document.getElementById('totalPrice').innerHTML = 'Rp ' + total.toLocaleString('id-ID');

    // Update summary
    document.getElementById('summaryQuantity').innerHTML = quantity;
    document.getElementById('summarySubtotal').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('summaryTotal').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
}

// Update total when quantity changes
document.getElementById('quantity').addEventListener('input', updateTotal);
</script>
@endsection