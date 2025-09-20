@extends('layouts.user')

@section('title', 'Cek Status Pesanan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Cek Status Pesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Order Status Checker -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-search me-2"></i>Cek Status Pesanan
                </h5>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form action="{{ route('user.check-status') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="order_id" placeholder="Masukkan ID Pesanan"
                               value="{{ request('order_id') }}" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-2"></i>Cek Status
                        </button>
                    </div>
                    <div class="form-text">
                        Masukkan ID pesanan Anda untuk melihat status terkini
                    </div>
                </form>

                <!-- Search Results -->
                @if($searchPerformed)
                    @if($order)
                        <!-- Order Found -->
                        <div class="alert alert-success">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-check-circle me-2"></i>Pesanan Ditemukan!
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                                    <p class="mb-1"><strong>Produk:</strong> {{ $order->product ? $order->product->name : 'Produk Dihapus' }}</p>
                                    <p class="mb-1"><strong>Jumlah:</strong> {{ $order->quantity }} pcs</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    <p class="mb-1"><strong>Dipesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                                    <p class="mb-0">
                                        <strong>Status:</strong>
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
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="text-end">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Lihat Detail Lengkap
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Order Not Found -->
                        <div class="alert alert-danger">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Pesanan Tidak Ditemukan
                            </h6>
                            <p class="mb-2">ID pesanan yang Anda masukkan tidak ditemukan atau bukan milik Anda.</p>
                            <div class="mb-0">
                                <strong>Saran:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pastikan ID pesanan sudah benar</li>
                                    <li>Pesanan hanya bisa dicek oleh pemiliknya</li>
                                    <li>Cek di <a href="{{ route('user.orders') }}">Riwayat Pesanan</a> untuk daftar lengkap</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="col-lg-4 mb-4">
        <!-- Recent Orders -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Pesanan Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentOrders as $recentOrder)
                            <a href="{{ route('user.orders.show', $recentOrder->id) }}"
                               class="list-group-item list-group-item-action px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold">#{{ $recentOrder->id }}</div>
                                        <small class="text-muted">
                                            {{ $recentOrder->product ? $recentOrder->product->name : 'Produk Dihapus' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'proses' => 'info',
                                                'selesai' => 'success',
                                                'ditolak' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$recentOrder->status] ?? 'secondary' }} small">
                                            {{ ucfirst($recentOrder->status) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ route('user.orders') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Pesanan
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-0">Belum ada pesanan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Guide -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Status Pesanan
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge bg-warning me-2">Pending</span>
                    <small>Menunggu konfirmasi admin</small>
                </div>
                <div class="mb-2">
                    <span class="badge bg-info me-2">Proses</span>
                    <small>Sedang dikerjakan</small>
                </div>
                <div class="mb-2">
                    <span class="badge bg-success me-2">Selesai</span>
                    <small>Siap diambil/dikirim</small>
                </div>
                <div class="mb-0">
                    <span class="badge bg-danger me-2">Ditolak</span>
                    <small>Pesanan tidak dapat diproses</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-question-circle text-primary me-2"></i>Bantuan
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Cara Cek Status:</h6>
                        <ol class="mb-0 small">
                            <li>Masukkan ID pesanan di kolom pencarian</li>
                            <li>Klik tombol "Cek Status"</li>
                            <li>Lihat status dan detail pesanan</li>
                            <li>Klik "Lihat Detail Lengkap" untuk informasi lebih lengkap</li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6>Tidak Tahu ID Pesanan?</h6>
                        <p class="mb-1 small">Kunjungi <a href="{{ route('user.orders') }}">Riwayat Pesanan</a> untuk melihat semua pesanan Anda dengan ID yang jelas.</p>
                        <p class="mb-0 small">Atau hubungi admin jika mengalami kesulitan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection