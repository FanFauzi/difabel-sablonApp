@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Detail Pesanan #{{ $order->id }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>ID Pesanan:</strong></div>
                    <div class="col-md-8">#{{ $order->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Pelanggan:</strong></div>
                    <div class="col-md-8">{{ $order->user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Produk:</strong></div>
                    <div class="col-md-8">{{ $order->product->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Jumlah:</strong></div>
                    <div class="col-md-8">{{ $order->quantity }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Harga per Unit:</strong></div>
                    <div class="col-md-8">Rp {{ number_format($order->product->price, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Biaya Desain:</strong></div>
                    <div class="col-md-8">Rp {{ number_format($order->design_cost, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Total Harga:</strong></div>
                    <div class="col-md-8">
                        <h4 class="text-success mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Status:</strong></div>
                    <div class="col-md-8">
                        @php
                            $statusColors = ['pending' => 'warning', 'proses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tanggal Pesan:</strong></div>
                    <div class="col-md-8">{{ $order->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Desain Kustom</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Ukuran Desain:</strong></div>
                    <div class="col-md-8">{{ ucfirst($order->design_size) }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Deskripsi Desain:</strong></div>
                    <div class="col-md-8">{{ $order->design_description }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>File Desain:</strong></div>
                    <div class="col-md-8">
                        @if ($order->design_file)
                            <a href="{{ asset('storage/' . $order->design_file) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-download me-1"></i> Lihat File
                            </a>
                        @else
                            <span class="text-muted">Tidak ada file desain.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Opsi Admin</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-edit me-1"></i> Perbarui Status
                </a>
                <a href="{{ route('admin.orders.delete', $order->id) }}" class="btn btn-danger w-100 mb-2">
                    <i class="fas fa-trash me-1"></i> Hapus Pesanan
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection