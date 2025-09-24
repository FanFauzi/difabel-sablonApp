<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; color: #333; }
        h2 { text-align: center; margin-bottom: 10px; }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 0;
            font-size: 14px;
            color: #444;
            margin-bottom: 8px;
        }
        .stats { display: flex; justify-content: space-between; }
        .stat-box {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin: 0 5px;
            text-align: center;
            background: #fff;
        }
        .stat-title { font-size: 11px; color: #666; margin-bottom: 4px; }
        .stat-value { font-size: 14px; font-weight: bold; color: #222; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 11px; }
        th { background: #f2f2f2; text-align: center; }
        td { text-align: center; }
        .text-right { text-align: right; }
        .no-data { text-align: center; font-style: italic; color: #888; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>

    <!-- Ringkasan -->
    <div class="card">
        <h3>Ringkasan</h3>
        <div class="stats">
            <div class="stat-box">
                <div class="stat-title">Total Pemasukan</div>
                <div class="stat-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Total Pesanan</div>
                <div class="stat-value">{{ $allOrdersCount }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Pesanan Selesai</div>
                <div class="stat-value">{{ $completedOrdersCount }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-title">Rata-rata Pesanan</div>
                <div class="stat-value">
                    Rp {{ $totalOrders > 0 ? number_format($totalIncome / $totalOrders, 0, ',', '.') : '0' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Bulanan -->
    <div class="card">
        <h3>Laporan Bulanan</h3>
        <table>
            <thead>
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
                        <td>{{ $report['month'] }}</td>
                        <td>{{ $report['total_orders'] }}</td>
                        <td>{{ $report['completed_orders'] }}</td>
                        <td class="text-right">Rp {{ number_format($report['income'], 0, ',', '.') }}</td>
                        <td class="text-right">
                            Rp {{ $report['total_orders'] > 0 ? number_format($report['income'] / $report['total_orders'], 0, ',', '.') : '0' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
