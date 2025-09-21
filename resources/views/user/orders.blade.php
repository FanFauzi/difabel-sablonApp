@extends('layouts.user')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div>
            <h2 class="mb-1">Riwayat Pesanan</h2>
            <p class="text-muted mb-0">Pantau status dan riwayat semua pesanan Anda</p>
        </div>
    </div>
</div>

@if($orders->count() > 0)
    <!-- Order Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-clock fa-lg text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ $orders->where('status', 'pending')->count() }}</h4>
                    <small class="text-muted">Menunggu</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-cog fa-lg text-info"></i>
                    </div>
                    <h4 class="mb-1">{{ $orders->where('status', 'proses')->count() }}</h4>
                    <small class="text-muted">Diproses</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ $orders->where('status', 'selesai')->count() }}</h4>
                    <small class="text-muted">Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-times-circle fa-lg text-danger"></i>
                    </div>
                    <h4 class="mb-1">{{ $orders->where('status', 'ditolak')->count() }}</h4>
                    <small class="text-muted">Ditolak</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Pesanan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pesanan</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($order->product && $order->product->image)
                                            <img src="{{ asset('storage/' . $order->product->image) }}"
                                                 alt="{{ $order->product->name }}"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-tshirt text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $order->product ? $order->product->name : 'Produk Dihapus' }}</div>
                                            @if($order->product && $order->product->category)
                                                <small class="text-muted">{{ $order->product->category }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order->quantity }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
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
                                    <div>
                                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('user.orders.show', $order->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum ada pesanan</h4>
                    <p class="text-muted mb-4">Anda belum membuat pesanan apapun. Mulai buat pesanan sablon custom pertama Anda!</p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Status Information -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>Informasi Status Pesanan
                </h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle p-2 me-3">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div>
                                <strong>Menunggu</strong>
                                <br><small class="text-muted">Pesanan sedang ditinjau</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-2 me-3">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <div>
                                <strong>Diproses</strong>
                                <br><small class="text-muted">Pesanan sedang dikerjakan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success rounded-circle p-2 me-3">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div>
                                <strong>Selesai</strong>
                                <br><small class="text-muted">Pesanan telah selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle p-2 me-3">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                            <div>
                                <strong>Ditolak</strong>
                                <br><small class="text-muted">Pesanan tidak dapat diproses</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
