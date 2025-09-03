@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Dashboard Pesanan</h2>
                <p class="text-muted mb-0">Pantau dan kelola semua pesanan sablon custom Anda</p>
            </div>
            <div class="text-end">
                <small class="text-muted">{{ now()->format('l, d F Y') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Pesanan Milik User -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Ringkasan Pesanan Milik {{ Auth::user()->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="display-4 text-primary mb-2">{{ $totalOrders }}</div>
                            <h6 class="text-muted">Total Pesanan</h6>
                            <small class="text-primary">Semua pesanan Anda</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <div class="display-4 text-warning mb-2">{{ $pendingOrders }}</div>
                            <h6 class="text-warning">Menunggu Konfirmasi</h6>
                            <small class="text-muted">Perlu ditinjau admin</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <div class="display-4 text-info mb-2">{{ $processingOrders }}</div>
                            <h6 class="text-info">Sedang Diproses</h6>
                            <small class="text-muted">Dalam pengerjaan</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <div class="display-4 text-success mb-2">{{ $completedOrders }}</div>
                            <h6 class="text-success">Telah Selesai</h6>
                            <small class="text-muted">Siap diambil/dikirim</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('user.products') }}" class="btn btn-primary w-100">
                            <i class="fas fa-tshirt me-2"></i>
                            <div>Lihat Produk</div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('user.orders.create', \App\Models\Product::first()?->id ?? 1) }}" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i>
                            <div>Buat Pesanan</div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('user.orders') }}" class="btn btn-info w-100">
                            <i class="fas fa-list me-2"></i>
                            <div>Riwayat Pesanan</div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('user.profile') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-user me-2"></i>
                            <div>Edit Profil</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Pesanan Terakhir -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Status Pesanan Terakhir
                </h5>
            </div>
            <div class="card-body">
                @if($latestOrder)
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-1">Pesanan #{{ $latestOrder->id }}</h5>
                            <p class="mb-2">
                                <strong>Produk:</strong> {{ $latestOrder->product ? $latestOrder->product->name : 'Produk Sablon' }}
                            </p>
                            <p class="mb-2">
                                <strong>Jumlah:</strong> {{ $latestOrder->quantity }} pcs
                            </p>
                            <p class="mb-2">
                                <strong>Total:</strong> <span class="text-success fw-bold">Rp {{ number_format($latestOrder->total_price, 0, ',', '.') }}</span>
                            </p>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Dipesan: {{ $latestOrder->created_at->format('d M Y H:i') }}
                            </small>
                        </div>
                        <div class="col-md-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'proses' => 'info',
                                    'selesai' => 'success',
                                    'ditolak' => 'danger'
                                ];
                                $statusText = [
                                    'pending' => 'Menunggu Konfirmasi',
                                    'proses' => 'Sedang Diproses',
                                    'selesai' => 'Telah Selesai',
                                    'ditolak' => 'Ditolak'
                                ];
                            @endphp
                            <div class="mb-3">
                                <span class="badge bg-{{ $statusColors[$latestOrder->status] ?? 'secondary' }} fs-6 px-4 py-2">
                                    {{ $statusText[$latestOrder->status] ?? ucfirst($latestOrder->status) }}
                                </span>
                            </div>
                            <a href="{{ route('user.orders.show', $latestOrder->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-shopping-cart text-muted fa-lg"></i>
                        </div>
                        <h6 class="text-muted mb-2">Belum ada pesanan</h6>
                        <p class="text-muted mb-3">Anda belum membuat pesanan apapun</p>
                        <a href="{{ route('user.products') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat Pesanan Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Info Pembayaran / Saldo (Optional) -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-wallet me-2"></i>Info Pembayaran & Saldo
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-success mb-2">
                                <i class="fas fa-coins fa-2x"></i>
                            </div>
                            <h4 class="text-success mb-1">Rp 0</h4>
                            <small class="text-muted">Saldo Tersedia</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-primary mb-2">
                                <i class="fas fa-credit-card fa-2x"></i>
                            </div>
                            <h4 class="text-primary mb-1">{{ $totalOrders }}</h4>
                            <small class="text-muted">Total Transaksi</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-info mb-2">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <h4 class="text-info mb-1">{{ $pendingOrders }}</h4>
                            <small class="text-muted">Menunggu Pembayaran</small>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Sistem pembayaran akan diaktifkan setelah pesanan dikonfirmasi oleh admin.
                    Saat ini semua transaksi menggunakan sistem COD (Cash on Delivery).
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Pesanan Terbaru</h5>
                <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($totalOrders > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Produk</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(Auth::user()->orders()->latest()->take(5)->get() as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->id }}</strong>
                                        </td>
                                        <td>
                                            {{ $order->orderItems->first()?->product->name ?? 'Produk Sablon' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'proses' => 'info',
                                                    'selesai' => 'success',
                                                    'ditolak' => 'danger'
                                                ];
                                                $statusText = [
                                                    'pending' => 'Menunggu',
                                                    'proses' => 'Diproses',
                                                    'selesai' => 'Selesai',
                                                    'ditolak' => 'Ditolak'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada pesanan</h5>
                        <p class="text-muted">Mulai buat pesanan sablon custom pertama Anda</p>
                        <a href="{{ route('user.products') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat Pesanan Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection