@extends('layouts.admin')

@section('title', 'Hapus Pesanan - ' . $order->id)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Manajemen Pesanan</a></li>
                <li class="breadcrumb-item active">Hapus Pesanan</li>
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
                    <p class="mb-0">Anda akan menghapus pesanan ini secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detail Pesanan Yang Akan Dihapus</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Order Details -->
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shopping-cart me-2"></i>Informasi Pesanan
                            </h6>
                            <p class="mb-1"><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                            <p class="mb-1"><strong>Status:</strong>
                                @php
                                    $statusText = [
                                        'pending' => 'Menunggu',
                                        'proses' => 'Diproses',
                                        'selesai' => 'Selesai',
                                        'ditolak' => 'Ditolak'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $order->getStatusColor() }}">
                                    {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-0"><strong>Total:</strong> <span class="text-success fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Informasi Pelanggan
                            </h6>
                            <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            <p class="mb-0"><strong>Role:</strong>
                                <span class="badge bg-{{ $order->user->role === 'admin' ? 'danger' : 'info' }}">
                                    {{ ucfirst($order->user->role) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="mt-3">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-box me-2"></i>Detail Produk
                    </h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            @if($order->product)
                                <div class="row align-items-center">
                                    @if($order->product->image)
                                        <div class="col-md-2 text-center mb-3">
                                            <img src="{{ asset('storage/' . $order->product->image) }}"
                                                 alt="{{ $order->product->name }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 60px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="col-md-{{ $order->product->image ? '10' : '12' }}">
                                        <h6 class="mb-1">{{ $order->product->name }}</h6>
                                        @if($order->product->category)
                                            <small class="text-muted">{{ $order->product->category }}</small>
                                        @endif
                                        <div class="mt-2">
                                            <span class="badge bg-secondary me-2">{{ $order->quantity }} unit</span>
                                            <span class="text-muted">× Rp {{ number_format($order->product->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-exclamation-triangle mb-2"></i>
                                    <p class="mb-0">Produk tidak ditemukan atau telah dihapus</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Design Information (if exists) -->
                @if($order->design_description || $order->design_file)
                    <div class="mt-3">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-palette me-2"></i>Informasi Desain
                        </h6>
                        <div class="alert alert-info">
                            <h6>Informasi Desain:</h6>
                            @if($order->design_description)
                                <p class="mb-2"><strong>Deskripsi:</strong> {{ $order->design_description }}</p>
                            @endif
                            @if($order->design_file)
                                <p class="mb-2"><strong>File:</strong> <em>{{ basename($order->design_file) }}</em></p>
                                <a href="{{ route('orders.download-design', $order->id) }}"
                                   class="btn btn-success btn-sm" target="_blank">
                                    <i class="fas fa-download me-1"></i>Download File Desain
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Notes (if exists) -->
                @if($order->notes)
                    <div class="mt-3">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-sticky-note me-2"></i>Catatan Admin
                        </h6>
                        <div class="alert alert-warning">
                            <strong>Catatan:</strong><br>
                            {{ $order->notes }}
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
                    <strong>Perhatian!</strong> Setelah dihapus, data pesanan ini tidak dapat dikembalikan.
                    Pastikan Anda benar-benar ingin menghapus pesanan ini.
                </div>

                <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label" for="confirmDelete">
                            <strong>Saya yakin ingin menghapus pesanan ini</strong>
                        </label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                            <i class="fas fa-trash me-2"></i>Hapus Pesanan
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