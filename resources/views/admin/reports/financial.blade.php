@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Laporan Keuangan</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.financial.export.excel') }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a>
            <a href="{{ route('reports.financial.export.pdf') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pemasukan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-calendar me-1"></i>Dari semua pesanan selesai
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allOrdersCount }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-shopping-cart me-1"></i>Semua status pesanan
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
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pesanan Selesai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $completedOrdersCount }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-check-circle me-1"></i>Pesanan yang telah selesai
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-info"></i>
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
                                Rata-rata Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ $totalOrders > 0 ? number_format($totalIncome / $totalOrders, 0, ',', '.') : '0' }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <i class="fas fa-chart-line me-1"></i>Per pesanan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Period Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-filter me-2"></i>Pilih Periode Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-select" id="reportPeriod">
                                <option value="monthly">Laporan Bulanan</option>
                                <option value="daily">Laporan Harian</option>
                                <option value="yearly">Laporan Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="month" class="form-control" id="reportMonth" value="{{ date('Y-m') }}">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" onclick="generateReport()">
                                <i class="fas fa-search me-2"></i>Generate Laporan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Report Table -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Laporan Bulanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="monthlyReportTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Total Pesanan</th>
                                    <th>Pesanan Selesai</th>
                                    <th>Pemasukan</th>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyReports as $report)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $report['month'] }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $report['total_orders'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $report['completed_orders'] }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success">
                                                Rp {{ number_format($report['income'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                Rp {{ $report['total_orders'] > 0 ? number_format($report['income'] / $report['total_orders'], 0, ',', '.') : '0' }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Tidak ada data laporan untuk periode ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Insights -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-lightbulb me-2"></i>Wawasan Keuangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Performa Bulan Ini</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                        </div>
                        <small class="text-muted">Target pemasukan tercapai</small>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Tren Pesanan</h6>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-arrow-up text-success me-2"></i>
                            <span class="text-success fw-bold">+15%</span>
                            <small class="text-muted ms-2">dari bulan lalu</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Produk Terlaris</h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div>
                                <div class="fw-bold small">Kaos Custom</div>
                                <small class="text-muted">45 pesanan</small>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="text-center">
                        <small class="text-muted">Laporan terakhir diupdate: {{ now()->format('d M Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Report (Hidden by default, shown when daily is selected) -->
    <div class="row" id="dailyReportSection" style="display: none;">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day me-2"></i>Laporan Harian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Fitur laporan harian akan tersedia dalam pembaruan mendatang.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    const period = document.getElementById('reportPeriod').value;
    const month = document.getElementById('reportMonth').value;

    if (period === 'daily') {
        document.getElementById('dailyReportSection').style.display = 'block';
        document.getElementById('monthlyReportTable').parentElement.parentElement.parentElement.style.display = 'none';
    } else {
        document.getElementById('dailyReportSection').style.display = 'none';
        document.getElementById('monthlyReportTable').parentElement.parentElement.parentElement.style.display = 'block';
    }

    // Here you could add AJAX calls to fetch data for different periods
    console.log('Generating report for:', period, month);
}

document.getElementById('reportPeriod').addEventListener('change', function() {
    const period = this.value;
    const monthInput = document.getElementById('reportMonth');

    if (period === 'yearly') {
        monthInput.type = 'number';
        monthInput.placeholder = 'Tahun (2024)';
        monthInput.value = new Date().getFullYear();
    } else {
        monthInput.type = 'month';
        monthInput.value = new Date().toISOString().slice(0, 7);
    }
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