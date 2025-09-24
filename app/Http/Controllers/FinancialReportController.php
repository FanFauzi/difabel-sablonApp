<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Base query for orders
        $ordersQuery = Order::where('status', 'selesai');

        // Apply date filters
        if ($period === 'today') {
            $ordersQuery->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $ordersQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $ordersQuery->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'year') {
            $ordersQuery->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $completedOrders = $ordersQuery->get();

        // Calculate financial metrics
        $totalIncome = $completedOrders->sum('total_price');
        $totalOrders = $completedOrders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalIncome / $totalOrders : 0;

        // Monthly income data for chart
        $monthlyData = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as income')
            ->where('status', 'selesai')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        // Recent transactions
        $recentTransactions = Order::with('user')
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top selling products
        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as total_revenue')
            ->where('orders.status', 'selesai')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Get all orders count for statistics
        $allOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'selesai')->count();

        // Format monthly data for the view
        $monthlyReports = $monthlyData->map(function ($item) {
            return [
                'month' => date('M Y', strtotime($item->year . '-' . $item->month . '-01')),
                'total_orders' => Order::whereYear('created_at', $item->year)
                    ->whereMonth('created_at', $item->month)
                    ->count(),
                'completed_orders' => Order::where('status', 'selesai')
                    ->whereYear('created_at', $item->year)
                    ->whereMonth('created_at', $item->month)
                    ->count(),
                'income' => $item->income ?? 0
            ];
        });

        return view('admin.reports.financial', compact(
            'totalIncome',
            'totalOrders',
            'completedOrdersCount',
            'allOrdersCount',
            'pendingOrdersCount',
            'averageOrderValue',
            'monthlyReports',
            'recentTransactions',
            'topProducts',
            'period',
            'startDate',
            'endDate'
        ));
    }

    public function exportExcel(Request $request)
    {
        $period = $request->get('period', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        return Excel::download(new FinancialReportExport($period, $startDate, $endDate), 'laporan_keuangan_' . date('Y-m-d') . '.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $period = $request->get('period', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get data for PDF
        $ordersQuery = Order::where('status', 'selesai');

        if ($period === 'today') {
            $ordersQuery->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $ordersQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $ordersQuery->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'year') {
            $ordersQuery->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $ordersQuery->with('user')->get();
        $totalIncome = $orders->sum('total_price');
        $totalOrders = $orders->count();
        
        // Add missing statistics that the PDF view requires
        $allOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'selesai')->count();
        $averageOrderValue = $totalOrders > 0 ? $totalIncome / $totalOrders : 0;

        // FIX: Add monthly report data calculation
        $monthlyData = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_price) as income')
            ->where('status', 'selesai')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        $monthlyReports = $monthlyData->map(function ($item) {
            return [
                'month' => date('M Y', strtotime($item->year . '-' . $item->month . '-01')),
                'total_orders' => Order::whereYear('created_at', $item->year)
                    ->whereMonth('created_at', $item->month)
                    ->count(),
                'completed_orders' => Order::where('status', 'selesai')
                    ->whereYear('created_at', $item->year)
                    ->whereMonth('created_at', $item->month)
                    ->count(),
                'income' => $item->income ?? 0
            ];
        });

        $pdf = Pdf::loadView('admin.reports.financial_pdf', compact(
            'orders',
            'totalIncome',
            'totalOrders',
            'allOrdersCount',
            'pendingOrdersCount',
            'completedOrdersCount',
            'averageOrderValue',
            'monthlyReports', 
            'period',
            'startDate',
            'endDate'
        ));

        return $pdf->download('laporan_keuangan_' . date('Y-m-d') . '.pdf');
    }
}

