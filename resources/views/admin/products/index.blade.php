@extends('layouts.admin')

@section('title', 'Manajemen Produk Sablon')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Manajemen Produk Sablon</h2>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Tambah Produk Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Produk
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tshirt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Stok
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->sum('stock') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Produk Habis
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('stock', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stok Rendah
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('stock', '<=', 10)->where('stock', '>', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Produk Sablon
            </h6>
            <div class="d-flex">
                <input type="text" class="form-control form-control-sm me-2" id="searchInput" placeholder="Cari produk...">
                <select class="form-select form-select-sm" id="categoryFilter">
                    <option value="">Semua Kategori</option>
                    <option value="kaos">Kaos</option>
                    <option value="kemeja">Kemeja</option>
                    <option value="jaket">Jaket</option>
                    <option value="topi">Topi</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="productsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($product->category) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $product->stock }}</span>
                                        @if($product->stock == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($product->stock <= 10)
                                            <span class="badge bg-warning">Rendah</span>
                                        @else
                                            <span class="badge bg-success">Tersedia</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($product->stock > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-pause-circle me-1"></i>Tidak Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Produk">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('products.delete', $product->id) }}" class="btn btn-sm btn-outline-danger" title="Hapus Produk" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada produk ditemukan.</p>
                                    <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus me-2"></i>Tambah Produk Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#productsTable tbody tr');

    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Category filter functionality
document.getElementById('categoryFilter').addEventListener('change', function() {
    const selectedCategory = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#productsTable tbody tr');

    tableRows.forEach(row => {
        if (!selectedCategory) {
            row.style.display = '';
            return;
        }

        const categoryBadge = row.querySelector('.badge');
        const categoryText = categoryBadge ? categoryBadge.textContent.toLowerCase() : '';
        row.style.display = categoryText.includes(selectedCategory) ? '' : 'none';
    });
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-primary {
    color: #5a5c69 !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.font-weight-bold {
    font-weight: 700 !important;
}
</style>
@endsection