@extends('layouts.admin')

@section('title', 'Detail Pesanan - ' . $order->id)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Manajemen Pesanan</a></li>
                <li class="breadcrumb-item active">Detail Pesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Pesanan #{{ $order->id }}</h5>
                <span class="badge bg-{{ $order->getStatusColor() }} fs-6">
                    @php
                        $statusText = [
                            'pending' => 'Menunggu',
                            'proses' => 'Diproses',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak'
                        ];
                    @endphp
                    {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Customer Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user me-2"></i>Informasi Pelanggan
                        </h6>
                        <div class="ps-3">
                            <p class="mb-1"><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            <p class="mb-1"><strong>Role:</strong>
                                <span class="badge bg-{{ $order->user->role === 'admin' ? 'danger' : 'info' }}">
                                    {{ ucfirst($order->user->role) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-shopping-cart me-2"></i>Informasi Pesanan
                        </h6>
                        <div class="ps-3">
                            <p class="mb-1"><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                            <p class="mb-1"><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            @if($order->updated_at != $order->created_at)
                                <p class="mb-1"><strong>Terakhir Update:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-box me-2"></i>Detail Produk
                    </h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row align-items-center">
                                @if($order->product)
                                    @if($order->product->image)
                                        <div class="col-md-2 text-center mb-3">
                                            <img src="{{ asset('storage/' . $order->product->image) }}"
                                                 alt="{{ $order->product->name }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 80px; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div class="col-md-{{ $order->product->image ? '10' : '12' }}">
                                        <h6 class="mb-1">{{ $order->product->name }}</h6>
                                        @if($order->product->category)
                                            <p class="text-muted mb-1">{{ $order->product->category }}</p>
                                        @endif
                                        @if($order->product->description)
                                            <p class="mb-2">{{ Str::limit($order->product->description, 100) }}</p>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <strong>Jumlah:</strong> {{ $order->quantity }} unit
                                            </div>
                                            <div class="col-sm-4">
                                                <strong>Harga:</strong> Rp {{ number_format($order->product->price, 0, ',', '.') }}
                                            </div>
                                            <div class="col-sm-4">
                                                <strong>Subtotal:</strong> Rp {{ number_format($order->product->price * $order->quantity, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                            <p>Produk tidak ditemukan atau telah dihapus</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Design Information -->
                @if($order->design_description || $order->design_file)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-palette me-2"></i>Informasi Desain Kustom
                        </h6>
                        <div class="card">
                            <div class="card-body">
                                @if($order->design_description)
                                    <div class="mb-3">
                                        <strong>Deskripsi Desain:</strong>
                                        <p class="mt-2">{{ $order->design_description }}</p>
                                    </div>
                                @endif

                                @if($order->design_file)
                                    <div class="mb-0">
                                        <strong>File Desain:</strong><br>
                                        <div class="mt-2">
                                            <a href="{{ route('orders.download-design', $order->id) }}"
                                               class="btn btn-success btn-sm me-2">
                                                <i class="fas fa-download me-1"></i>Download File
                                            </a>
                                            <small class="text-muted">{{ basename($order->design_file) }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($order->notes)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-sticky-note me-2"></i>Catatan Admin
                        </h6>
                        <div class="alert alert-info">
                            <strong>Catatan:</strong><br>
                            {{ $order->notes }}
                        </div>
                    </div>
                @endif

                <!-- Order Items (if using order_items table) -->
                @if($order->orderItems && $order->orderItems->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-list me-2"></i>Detail Item Pesanan
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->product_name ?? 'Item ' . $item->id }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Summary & Actions -->
    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Ringkasan Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya Admin:</span>
                    <strong>Rp 0</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-0">
                    <span class="h6">Total:</span>
                    <span class="h5 text-success mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Status
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Timeline Status</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            <p class="mb-0">Pesanan dibuat</p>
                        </div>
                    </div>

                    @if($order->status !== 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                                <p class="mb-0">Status diupdate ke "{{ $statusText[$order->status] ?? ucfirst($order->status) }}"</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px 15px;
    margin-left: 10px;
}
</style>
@endsection