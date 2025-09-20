@extends('layouts.user')

@section('title', 'Pilih Produk - Buat Pesanan')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
        </div>
    </div>

    @if ($products->count() > 0)
        <div class="row">
            @foreach ($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            @if ($product->image)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                </div>
                            @else
                                <div class="text-center mb-3">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i class="fas fa-tshirt fa-3x text-muted"></i>
                                    </div>
                                </div>
                            @endif

                            <h5 class="card-title">{{ $product->name }}</h5>

                            @if ($product->category)
                                <span class="badge bg-primary mb-2">{{ $product->category }}</span>
                            @endif

                            <p class="card-text text-muted small mb-2">
                                {{ Str::limit($product->description, 100) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong class="text-success">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <div class="d-grid">
                                {{-- Mengarahkan ke halaman desain dan pemesanan yang baru --}}
                                <a href="{{ route('user.orders.create', $product) }}" class="btn btn-primary">
                                    <i class="fas fa-magic me-2"></i>Desain & Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Tidak ada produk tersedia</h4>
                        <p class="text-muted">Saat ini tidak ada produk sablon yang tersedia untuk dipesan.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle text-primary me-2"></i>Informasi Pemesanan
                    </h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-upload text-success me-3"></i>
                                <div>
                                    <strong>Upload Desain</strong>
                                    <br><small class="text-muted">Kirim file desain sablon Anda</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-warning me-3"></i>
                                <div>
                                    <strong>Proses Cepat</strong>
                                    <br><small class="text-muted">Pesanan diproses dalam 1-2 hari</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-shield-alt text-info me-3"></i>
                                <div>
                                    <strong>Garansi Kualitas</strong>
                                    <br><small class="text-muted">Kualitas sablon terjamin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
