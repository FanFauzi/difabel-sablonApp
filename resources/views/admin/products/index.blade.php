@extends('layouts.admin')

@section('title', 'Manajemen Produk Sablon')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h2 class="mb-1">Manajemen Produk Sablon</h2></div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Tambah Produk Baru
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customProducts->count() }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-tshirt fa-2x text-primary"></i></div>
                    </div>
                </div>
            </div>
        </div>
        </div>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Daftar Produk Sablon</h6>
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
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Biaya Desain</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customProducts as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="fas fa-image text-muted"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <div><div class="fw-bold">{{ $product->name }}</div><small class="text-muted">{{ Str::limit($product->description, 50) }}</small></div>
                                </td>
                                <td><div class="fw-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</div></td>
                                <td>
                                    <div class="small">
                                        <div><small>S: Rp {{ number_format($product->small_design_cost, 0, ',', '.') }}</small></div>
                                        <div><small>M: Rp {{ number_format($product->medium_design_cost, 0, ',', '.') }}</small></div>
                                        <div><small>L: Rp {{ number_format($product->large_design_cost, 0, ',', '.') }}</small></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Produk"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.products.delete', $product->id) }}" class="btn btn-sm btn-outline-danger" title="Hapus Produk"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4"><i class="fas fa-tshirt fa-3x text-muted mb-3"></i><p class="text-muted mb-0">Tidak ada produk ditemukan.</p><a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3"><i class="fas fa-plus me-2"></i>Tambah Produk Pertama</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection