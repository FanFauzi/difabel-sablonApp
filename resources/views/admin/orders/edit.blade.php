@extends('layouts.admin')

@section('title', 'Edit Pesanan - ' . $order->id)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Manajemen Pesanan</a></li>
                <li class="breadcrumb-item active">Edit Pesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID Pesanan:</strong><br>
                    <span class="badge bg-primary">#{{ $order->id }}</span>
                </div>

                <div class="mb-3">
                    <strong>Pelanggan:</strong><br>
                    <span class="text-primary">{{ $order->user->name }}</span><br>
                    <small class="text-muted">{{ $order->user->email }}</small>
                </div>

                <div class="mb-3">
                    <strong>Produk:</strong><br>
                    @if($order->product)
                        <div class="d-flex align-items-center">
                            @if($order->product->image)
                                <img src="{{ asset('storage/' . $order->product->image) }}"
                                     alt="{{ $order->product->name }}"
                                     class="rounded me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div>
                                <div class="fw-bold">{{ $order->product->name }}</div>
                                @if($order->product->category)
                                    <small class="text-muted">{{ $order->product->category }}</small>
                                @endif
                            </div>
                        </div>
                    @else
                        <span class="text-muted">Produk tidak ditemukan</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Jumlah:</strong><br>
                    <span class="badge bg-secondary">{{ $order->quantity }} unit</span>
                </div>

                <div class="mb-3">
                    <strong>Total Harga:</strong><br>
                    <h5 class="text-success mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                </div>

                <div class="mb-3">
                    <strong>Tanggal Pesan:</strong><br>
                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Update Status Pesanan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Current Status -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Status Saat Ini</h6>
                        <div class="alert alert-info">
                            <strong>Status:</strong>
                            @php
                                $statusText = [
                                    'pending' => 'Menunggu Konfirmasi',
                                    'proses' => 'Sedang Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak'
                                ];
                            @endphp
                            <span class="badge bg-{{ $order->getStatusColor() }} ms-2">{{ $statusText[$order->status] ?? ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <!-- New Status -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Update Status</h6>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Baru <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                @foreach(\App\Models\Order::getStatusOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Pilih status baru untuk pesanan ini
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Catatan Admin</h6>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4"
                                      placeholder="Tambahkan catatan untuk pesanan ini...">{{ $order->notes }}</textarea>
                            <div class="form-text">
                                Catatan ini akan tersimpan dan dapat dilihat oleh pelanggan
                            </div>
                        </div>
                    </div>

                    <!-- Design Information (if exists) -->
                    @if($order->design_description || $order->design_file)
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">Informasi Desain</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if($order->design_description)
                                        <div class="mb-3">
                                            <strong>Deskripsi Desain:</strong><br>
                                            <p class="mb-0">{{ $order->design_description }}</p>
                                        </div>
                                    @endif

                                    @if($order->design_file)
                                        <div class="mb-0">
                                            <strong>File Desain:</strong><br>
                                            <div class="mt-2">
                                                <a href="{{ route('orders.download-design', $order->id) }}"
                                                   class="btn btn-success btn-sm me-2" target="_blank">
                                                    <i class="fas fa-download me-1"></i>Download File Desain
                                                </a>
                                                <small class="text-muted">{{ basename($order->design_file) }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Status Change History -->
@if($order->updated_at != $order->created_at)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Riwayat Perubahan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Terakhir diupdate:</small><br>
                        <strong>{{ $order->updated_at->format('d/m/Y H:i:s') }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Dibuat pada:</small><br>
                        <strong>{{ $order->created_at->format('d/m/Y H:i:s') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection