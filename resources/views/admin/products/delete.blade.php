@extends('layouts.admin')

@section('title', 'Hapus Produk - ' . $product->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Manajemen Produk</a></li>
                <li class="breadcrumb-item active">Hapus Produk</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Warning Alert -->
        <div class="alert alert-danger" role="alert">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">Peringatan!</h5>
                    <p class="mb-0">Anda akan menghapus produk ini secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detail Produk Yang Akan Dihapus</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Product Image and Basic Info -->
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
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
                            <h5 class="mb-1">{{ $product->name }}</h5>
                            @if($product->category)
                                <span class="badge bg-primary">{{ $product->category }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Produk
                                    </h6>
                                    <p class="mb-1"><strong>ID Produk:</strong> #{{ $product->id }}</p>
                                    <p class="mb-1"><strong>Harga:</strong> <span class="text-success fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span></p>
                                    <p class="mb-1"><strong>Stok:</strong>
                                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                            {{ $product->stock }} unit
                                        </span>
                                    </p>
                                    <p class="mb-0"><strong>Status:</strong>
                                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'warning' }}">
                                            {{ $product->stock > 0 ? 'Tersedia' : 'Stok Habis' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="border rounded p-3">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-calendar me-2"></i>Informasi Waktu
                                    </h6>
                                    <p class="mb-1"><strong>Dibuat:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                                    @if($product->updated_at != $product->created_at)
                                        <p class="mb-0"><strong>Diupdate:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Product Description -->
                        @if($product->description)
                            <div class="mt-3">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-align-left me-2"></i>Deskripsi Produk
                                </h6>
                                <div class="alert alert-info">
                                    <p class="mb-0">{{ $product->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Orders Warning -->
                @php
                    $relatedOrdersCount = \App\Models\Order::where('product_id', $product->id)->count();
                @endphp
                @if($relatedOrdersCount > 0)
                    <div class="mt-3">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Perhatian!
                            </h6>
                            <p class="mb-1">Produk ini memiliki <strong>{{ $relatedOrdersCount }}</strong> pesanan terkait.</p>
                            <p class="mb-0">Menghapus produk ini tidak akan menghapus pesanan yang sudah ada, namun produk tidak akan tersedia untuk pemesanan baru.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Confirmation Form -->
        <div class="card mt-4 border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0">
                    <i class="fas fa-trash me-2"></i>Konfirmasi Penghapusan
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <strong>Perhatian!</strong> Setelah dihapus, produk ini tidak dapat dikembalikan.
                    Pastikan Anda benar-benar ingin menghapus produk ini.
                </div>

                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            <strong>Saya yakin ingin menghapus produk "{{ $product->name }}"</strong>
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                            <i class="fas fa-trash me-2"></i>Hapus Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('confirmDelete').addEventListener('change', function() {
    document.getElementById('deleteBtn').disabled = !this.checked;
});
</script>
@endsection