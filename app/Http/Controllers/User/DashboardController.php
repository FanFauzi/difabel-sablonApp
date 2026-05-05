<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Hitung statistik pesanan berdasarkan status
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $processingOrders = $user->orders()->where('status', 'proses')->count();
        $completedOrders = $user->orders()->where('status', 'selesai')->count();

        // Ambil 1 pesanan terakhir beserta data produknya
        $latestOrder = $user->orders()->latest()->first();

        // Kirim semua variabel ke view user.dashboard
        return view('user.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'latestOrder'
        ));
    }
}