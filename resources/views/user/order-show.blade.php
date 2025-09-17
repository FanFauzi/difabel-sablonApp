@extends('layouts.user')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.orders') }}">Riwayat Pesanan</a></li>
                <li class="breadcrumb-item active">Detail Pesanan #{{ $order->id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Detail Pesanan #{{ $order->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        @if($order->product && $order->product->image)
                            <img src="{{ asset('storage/' . $order->product->image) }}"
                                 alt="{{ $order->product->name }}"
                                 class="img-fluid rounded mb-3"
                                 style="max-height: 120px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 120px;">
                                <i class="fas fa-tshirt fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h5 class="mb-2">{{ $order->product ? $order->product->name : 'Produk Dihapus' }}</h5>
                        @if($order->product && $order->product->category)
                            <span class="badge bg-primary mb-2">{{ $order->product->category }}</span>
                        @endif
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Jumlah:</strong> {{ $order->quantity }} pcs</p>
                                <p class="mb-1"><strong>Harga per Unit:</strong> Rp {{ number_format($order->product ? $order->product->price : 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Total Harga:</strong></p>
                                <h4 class="text-success mb-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <p class="mb-1"><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                        <p class="mb-0"><strong>Terakhir Update:</strong> {{ $order->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($order->design_description || $order->design_file)
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-palette me-2"></i>Informasi Desain
                    </h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            @if($order->design_size)
                                <p class="mb-2"><strong>Ukuran Desain:</strong> {{ ucfirst($order->design_size) }}</p>
                            @endif
                            @if($order->design_cost)
                                <p class="mb-2"><strong>Biaya Desain:</strong> Rp {{ number_format($order->design_cost, 0, ',', '.') }}</p>
                            @endif
                            @if($order->design_description)
                                <p class="mb-2"><strong>Deskripsi Desain:</strong></p>
                                <p class="mb-3">{{ $order->design_description }}</p>
                            @endif

                            @if($order->design_file)
                                <p class="mb-2"><strong>File Desain:</strong></p>
                                <a href="{{ asset('storage/' . $order->design_file) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download me-2"></i>Lihat File Desain
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($order->notes)
                <div class="mb-4">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-sticky-note me-2"></i>Catatan Tambahan
                    </h6>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-0">{{ $order->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Status Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
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
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6 px-4 py-2">
                            {{ $statusText[$order->status] ?? ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status === 'pending' || $order->status === 'proses' || $order->status === 'selesai' ? 'bg-warning' : 'bg-secondary' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Menunggu Konfirmasi</h6>
                            <p class="mb-1 small text-muted">Pesanan sedang ditinjau oleh admin</p>
                            @if($order->status === 'pending' || $order->status === 'proses' || $order->status === 'selesai')
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                </small>
                            @else
                                <small class="text-muted">Belum tercapai</small>
                            @endif
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status === 'proses' || $order->status === 'selesai' ? 'bg-info' : 'bg-secondary' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Sedang Diproses</h6>
                            <p class="mb-1 small text-muted">Pesanan sedang dikerjakan</p>
                            @if($order->status === 'proses' || $order->status === 'selesai')
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>
                                    @if($order->status === 'proses')
                                        {{ $order->updated_at->format('d/m/Y H:i') }}
                                    @else
                                        Dalam proses
                                    @endif
                                </small>
                            @else
                                <small class="text-muted">Menunggu konfirmasi</small>
                            @endif
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-marker {{ $order->status === 'selesai' ? 'bg-success' : 'bg-secondary' }}"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Telah Selesai</h6>
                            <p class="mb-1 small text-muted">Pesanan telah selesai dan siap diambil</p>
                            @if($order->status === 'selesai')
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>{{ $order->updated_at->format('d/m/Y H:i') }}
                                </small>
                            @else
                                <small class="text-muted">Dalam proses</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
                    </a>
                    @if($order->status === 'selesai')
                        <button class="btn btn-success w-100" disabled>
                            <i class="fas fa-check me-2"></i>Pesanan Selesai
                        </button>
                    @elseif($order->status === 'ditolak')
                        <a href="{{ route('user.products') }}" class="btn btn-warning w-100">
                            <i class="fas fa-redo me-2"></i>Buat Pesanan Baru
                        </a>
                    @else
                        <button class="btn btn-info w-100" disabled>
                            <i class="fas fa-clock me-2"></i>Menunggu Proses
                        </button>
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
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 14px;
    height: 14px;
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

.timeline-content h6 {
    color: #495057;
    font-weight: 600;
}
</style>
@endsection