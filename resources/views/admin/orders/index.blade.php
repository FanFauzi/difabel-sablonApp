@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="mb-4">
        <h2 class="mb-1">Manajemen Pesanan</h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Proses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status', 'proses')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Selesai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->where('status', 'selesai')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Daftar Semua Pesanan
            </h6>
            <div class="d-flex">
                <input type="text" class="form-control form-control-sm me-2" id="searchInput" placeholder="Cari pesanan...">
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $order->user->name }}</div>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($order->orderItems->count() > 0)
                                            <div class="fw-bold">{{ $order->orderItems->first()->product->name }}</div>
                                            @if($order->orderItems->count() > 1)
                                                <small class="text-muted">+{{ $order->orderItems->count() - 1 }} produk lainnya</small>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark fs-6">
                                        {{ $order->orderItems->sum('quantity') }} item
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">
                                        Rp {{ number_format($order->orderItems->sum(function($item) { return $item->quantity * $item->price; }), 0, ',', '.') }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg' => 'warning', 'text' => 'Pending', 'icon' => 'clock'],
                                            'proses' => ['bg' => 'info', 'text' => 'Proses', 'icon' => 'cog'],
                                            'selesai' => ['bg' => 'success', 'text' => 'Selesai', 'icon' => 'check-circle'],
                                            'ditolak' => ['bg' => 'danger', 'text' => 'Ditolak', 'icon' => 'times-circle']
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['bg' => 'secondary', 'text' => ucfirst($order->status), 'icon' => 'question-circle'];
                                    @endphp
                                    <span class="badge bg-{{ $config['bg'] }} fs-6">
                                        <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                        {{ $config['text'] }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div>{{ $order->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-warning" title="Ubah Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('orders.delete', $order->id) }}" class="btn btn-sm btn-outline-danger" title="Hapus Pesanan" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Tidak ada pesanan ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#ordersTable tbody tr');

    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Status filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const tableRows = document.querySelectorAll('#ordersTable tbody tr');

    tableRows.forEach(row => {
        if (!selectedStatus) {
            row.style.display = '';
            return;
        }

        const statusBadge = row.querySelector('.badge');
        const statusText = statusBadge ? statusBadge.textContent.toLowerCase() : '';
        row.style.display = statusText.includes(selectedStatus) ? '' : 'none';
    });
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-primary {
    color: #5a5c69 !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.font-weight-bold {
    font-weight: 700 !important;
}
</style>
@endsection