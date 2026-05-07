@extends('layouts.user')

@section('title', 'Invoice Pesanan #' . $order->order_number)

@section('content')
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0" id="invoice-card">
                <div class="card-header bg-primary text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-white">INVOICE PESANAN</h4>
                            <small class="opacity-75">ID: #{{ $order->order_number }}</small>
                        </div>
                        <div class="text-end text-white">
                            <h5 class="mb-0">{{ $order->created_at->format('d M Y') }}</h5>
                            <span class="badge bg-warning text-dark">{{ strtoupper($order->status) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 text-white">
                            <h6 class="text-muted text-uppercase small fw-bold">Dari:</h6>
                            <p class="mb-0"><strong>{{ config('app.name') }} Sablon</strong></p>
                            <p class="text-muted small">Workshop Sablon Difabel</p>
                        </div>
                        <div class="col-md-6 text-md-end text-white">
                            <h6 class="text-muted text-uppercase small fw-bold">Kepada:</h6>
                            <p class="mb-0"><strong>{{ Auth::user()->name }}</strong></p>
                            <p class="text-muted small">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3"><i class="fas fa-box me-2"></i>Rincian Produk</h6>
                    <table class="table table-borderless">
                        <thead>
                            <tr class="text-muted small">
                                <th>Deskripsi</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{{ $order->product->name }}</strong><br>
                                    <span class="badge bg-light text-dark border">Ukuran: {{ $order->size }}</span>
                                    <span class="badge bg-light text-dark border">Warna: {{ ucfirst($order->color) }}</span>
                                </td>
                                <td class="text-center">{{ $order->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_price / $order->quantity, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="border-top">
                                <td colspan="3" class="text-end fw-bold pt-3">TOTAL BAYAR:</td>
                                <td class="text-end fw-bold pt-3 text-primary h5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="mt-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-paint-brush me-2"></i>Preview Desain Anda</h6>
                        <div class="row g-2">
                            @if($order->design_file_depan)
                            <div class="col-4">
                                <div class="p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/' . $order->design_file_depan) }}" class="img-fluid rounded" alt="Depan">
                                    <p class="text-center small mb-0 mt-1">Depan</p>
                                </div>
                            </div>
                            @endif
                            @if($order->design_file_belakang)
                            <div class="col-4">
                                <div class="p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/' . $order->design_file_belakang) }}" class="img-fluid rounded" alt="Belakang">
                                    <p class="text-center small mb-0 mt-1">Belakang</p>
                                </div>
                            </div>
                            @endif
                            @if($order->design_file_samping)
                            <div class="col-4">
                                <div class="p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/' . $order->design_file_samping) }}" class="img-fluid rounded" alt="Samping">
                                    <p class="text-center small mb-0 mt-1">Samping</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white p-4">
                    <div class="alert alert-info small border-0 shadow-none">
                        <i class="fas fa-info-circle me-2"></i> Klik tombol di bawah ini untuk mengirim invoice ke Admin via WhatsApp agar pesanan segera diproses.
                    </div>
                    <div class="d-grid gap-2">
                        @php
                            $adminWA = config('app.admin_whatsapp'); 
                            $message = "Halo Admin Sablon Difabel, saya ingin konfirmasi pesanan saya.\n\n" .
                                       "*Nomor Pesanan:* #" . $order->order_number . "\n" .
                                       "*Nama:* " . Auth::user()->name . "\n" .
                                       "*Produk:* " . $order->product->name . " (" . $order->size . ")\n" .
                                       "*Warna:* " . ucfirst($order->color) . "\n" .
                                       "*Total:* Rp " . number_format($order->total_price, 0, ',', '.') . "\n\n" .
                                       "Mohon segera dicek ya Min. Terima kasih!";
                            $waLink = "https://wa.me/" . $adminWA . "?text=" . urlencode($message);
                        @endphp
                        
                        <a href="{{ $waLink }}" target="_blank" class="btn btn-success btn-lg">
                            <i class="fab fa-whatsapp me-2"></i> Konfirmasi ke WhatsApp Admin
                        </a>
                        
                        <div class="d-flex justify-content-between mt-2">
                            <a href="{{ route('user.orders') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-primary">
                                <i class="fas fa-print me-1"></i> Cetak PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, .btn, .alert, footer, .breadcrumb { display: none !important; }
        .card { border: none !important; }
        .card-header { background-color: #0d6efd !important; color: white !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection