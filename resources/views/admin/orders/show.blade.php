@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Detail Pesanan #{{ $order->id }}</h2>
            <p class="text-muted">Dipesan oleh: <strong>{{ $order->user->name ?? 'User Dihapus' }}</strong> pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Card Detail Pesanan --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Produk:</strong></div>
                        <div class="col-md-8">{{ $order->product->name ?? 'Produk Dihapus' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Jumlah:</strong></div>
                        <div class="col-md-8">{{ $order->quantity }} pcs</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Ukuran & Warna:</strong></div>
                        <div class="col-md-8">{{ $order->size ?? 'N/A' }} / {{ ucfirst($order->color ?? 'N/A') }}</div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Harga per Unit:</strong></div>
                        <div class="col-md-8">Rp {{ number_format($order->product->price ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Total Harga Produk:</strong></div>
                        <div class="col-md-8">Rp {{ number_format(($order->product->price ?? 0) * $order->quantity, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Biaya Desain:</strong></div>
                        <div class="col-md-8">Rp {{ number_format($order->design_cost ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>Total Pembayaran:</strong></div>
                        <div class="col-md-8">
                            <h4 class="text-success mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Detail Desain --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-paint-brush me-2"></i>Detail Desain Kustom</h5>
                </div>
                <div class="card-body">
                    @if($order->design_file_depan || $order->design_file_belakang || $order->design_file_samping)
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Catatan Pelanggan:</strong></div>
                            <div class="col-md-8">{{ $order->notes ?: 'Tidak ada catatan.' }}</div>
                        </div>

                        <h6 class="mt-4">Pratinjau Desain:</h6>
                        <div class="row text-center">
                            {{-- Tampilkan Pratinjau Desain Depan --}}
                            @if ($order->design_file_depan)
                                <div class="col-md-4 mb-3">
                                    <a href="{{ asset('storage/' . $order->design_file_depan) }}" target="_blank" data-bs-toggle="tooltip" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/' . $order->design_file_depan) }}" class="img-fluid rounded border" alt="Desain Depan">
                                    </a>
                                    <p class="mt-2 fw-bold">Depan</p>
                                </div>
                            @endif

                            {{-- Tampilkan Pratinjau Desain Belakang --}}
                            @if ($order->design_file_belakang)
                                <div class="col-md-4 mb-3">
                                    <a href="{{ asset('storage/' . $order->design_file_belakang) }}" target="_blank" data-bs-toggle="tooltip" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/' . $order->design_file_belakang) }}" class="img-fluid rounded border" alt="Desain Belakang">
                                    </a>
                                    <p class="mt-2 fw-bold">Belakang</p>
                                </div>
                            @endif

                            {{-- Tampilkan Pratinjau Desain Samping --}}
                            @if ($order->design_file_samping)
                                <div class="col-md-4 mb-3">
                                    <a href="{{ asset('storage/' . $order->design_file_samping) }}" target="_blank" data-bs-toggle="tooltip" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/' . $order->design_file_samping) }}" class="img-fluid rounded border" alt="Desain Samping">
                                    </a>
                                    <p class="mt-2 fw-bold">Samping</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">Pesanan ini tidak menyertakan desain kustom.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Card Opsi Admin --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Opsi Admin</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Status Saat Ini:</strong></label>
                        <span class="badge fs-6 {{ $order->getStatusBadgeClass() }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-edit me-1"></i> Perbarui Status
                    </a>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100 mb-2">
                            <i class="fas fa-trash me-1"></i> Hapus Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection