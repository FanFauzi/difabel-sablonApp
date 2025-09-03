@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Date Header -->
    <div class="row mb-4">
        <div class="col-12 text-end">
            <div class="bg-light rounded-pill px-4 py-2 d-inline-block shadow-sm">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                <span class="fw-bold text-dark">{{ now()->format('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small mb-1">TOTAL USERS</div>
                            <div class="h2 text-white mb-0 font-weight-bold">{{ \App\Models\User::count() }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-75">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            +12% dari bulan lalu
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 15px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small mb-1">PRODUK SABLON</div>
                            <div class="h2 text-white mb-0 font-weight-bold">{{ \App\Models\Product::count() }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-tshirt fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-75">
                            <i class="fas fa-plus text-info me-1"></i>
                            5 produk baru
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 15px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small mb-1">TOTAL PESANAN</div>
                            <div class="h2 text-white mb-0 font-weight-bold">{{ \App\Models\Order::count() }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-shopping-cart fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-75">
                            <i class="fas fa-clock text-warning me-1"></i>
                            3 menunggu konfirmasi
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 15px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 small mb-1">LOG AKTIVITAS</div>
                            <div class="h2 text-white mb-0 font-weight-bold">{{ \App\Models\ActivityLog::count() }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-circle p-3">
                            <i class="fas fa-history fa-2x text-white"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-75">
                            <i class="fas fa-eye text-primary me-1"></i>
                            24 aktivitas hari ini
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status & Financial Overview -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Status Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="card bg-gradient-warning text-white border-0" style="border-radius: 12px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="card-body text-center p-4">
                                    <div class="display-4 mb-2">{{ \App\Models\Order::where('status', 'pending')->count() }}</div>
                                    <h6 class="mb-0">Pending</h6>
                                    <small class="opacity-75">Menunggu konfirmasi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="card bg-gradient-info text-white border-0" style="border-radius: 12px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="card-body text-center p-4">
                                    <div class="display-4 mb-2">{{ \App\Models\Order::where('status', 'proses')->count() }}</div>
                                    <h6 class="mb-0">Proses</h6>
                                    <small class="opacity-75">Sedang dikerjakan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <div class="card bg-gradient-success text-white border-0" style="border-radius: 12px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <div class="card-body text-center p-4">
                                    <div class="display-4 mb-2">{{ \App\Models\Order::where('status', 'selesai')->count() }}</div>
                                    <h6 class="mb-0">Selesai</h6>
                                    <small class="opacity-75">Telah selesai</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-lg border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-gradient-success text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>Ringkasan Keuangan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Total Pemasukan</span>
                            <i class="fas fa-trending-up text-success"></i>
                        </div>
                        <div class="h3 mb-0 text-success font-weight-bold">
                            Rp {{ number_format(\App\Models\Order::where('status', 'selesai')->join('order_items', 'orders.id', '=', 'order_items.order_id')->sum(\DB::raw('order_items.quantity * order_items.price')), 0, ',', '.') }}
                        </div>
                        <small class="text-muted">Dari pesanan selesai</small>
                    </div>
                    <hr class="my-3">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Pesanan Bulan Ini</span>
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </div>
                        <div class="h4 mb-0 font-weight-bold">
                            {{ \App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}
                        </div>
                        <small class="text-primary">{{ now()->format('F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(\App\Models\ActivityLog::count() > 0)
                        <div class="timeline">
                            @foreach(\App\Models\ActivityLog::latest()->take(5)->get() as $log)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $log->action }}</h6>
                                                <p class="text-muted small mb-0">{{ $log->description ?? 'Aktivitas sistem' }}</p>
                                            </div>
                                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada aktivitas tercatat</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

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
    padding: 15px;
    margin-left: 10px;
}

.display-4 {
    font-size: 2.5rem;
    font-weight: 300;
    line-height: 1.2;
}

.font-weight-bold {
    font-weight: 700 !important;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.5) !important;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}
</style>
@endsection