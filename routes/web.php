<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\FinancialReportController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'user') {
            return redirect()->route('user.dashboard');
        }
    }
    return view('home');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Force logout route for testing
Route::get('/force-logout', function () {
    Auth::logout();
    session()->flush();
    return redirect('/login')->with('success', 'Berhasil logout. Silakan login kembali.');
})->name('force.logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/admin/home', function () {
        return view('home');
    })->name('admin.home');

    // Rute untuk manajemen produk standar
    Route::resource('products', CustomProductController::class);

    Route::get('/products/{id}/delete', function ($id) {
        $product = \App\Models\CustomProduct::findOrFail($id);
        return view('admin.products.delete', compact('product'));
    })->name('products.delete');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Management
    Route::get('/users', function () {
        $users = \App\Models\User::all();
        return view('admin.users.index', compact('users'));
    })->name('users.index');
    Route::get('/users/create', function () {
        return view('admin.users.create');
    })->name('users.create');
    Route::post('/users', function (Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    })->name('users.store');
    Route::get('/users/{id}/edit', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    })->name('users.edit');
    Route::put('/users/{id}', function (Illuminate\Http\Request $request, $id) {
        $user = \App\Models\User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    })->name('users.update');
    Route::get('/users/{id}/delete', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.delete', compact('user'));
    })->name('users.delete');
    Route::delete('/users/{id}', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus user yang sedang login!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    })->name('users.destroy');

    // Order Management
    Route::get('/orders', function () {
        $orders = \App\Models\Order::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    })->name('orders.index');
    Route::get('/orders/{id}', function ($id) {
        $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    })->name('orders.show');
    Route::get('/orders/{id}/edit', function ($id) {
        $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    })->name('orders.edit');
    Route::put('/orders/{id}', function (Illuminate\Http\Request $request, $id) {
        $order = \App\Models\Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,ditolak',
            'notes' => 'nullable|string',
        ]);
        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);
        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui!');
    })->name('orders.update');
    Route::get('/orders/{id}/delete', function ($id) {
        $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.delete', compact('order'));
    })->name('orders.delete');
    Route::delete('/orders/{id}', function ($id) {
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus!');
    })->name('orders.destroy');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');

    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogsController::class, 'index'])->name('index');
        Route::get('/{id}', [ActivityLogsController::class, 'show'])->name('show');
        Route::delete('/{id}', [ActivityLogsController::class, 'destroy'])->name('destroy');
        Route::delete('/', [ActivityLogsController::class, 'clear'])->name('clear');
    });

    // Financial Reports
    Route::get('/reports/financial', [FinancialReportController::class, 'index'])->name('reports.financial');
    Route::get('/reports/financial/export/excel', [FinancialReportController::class, 'exportExcel'])->name('reports.financial.export.excel');
    Route::get('/reports/financial/export/pdf', [FinancialReportController::class, 'exportPDF'])->name('reports.financial.export.pdf');

    // Design File Downloads
    Route::get('/orders/{order}/download-design', [OrderController::class, 'downloadDesign'])->name('orders.download-design');

});


Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/home', function () {
        return view('home');
    })->name('user.home');

    // Rute sederhana seperti dashboard bisa tetap di sini
    Route::get('user/dashboard', function () {
        $user = Auth::user();
        $summary = [
            'totalOrders' => $user->orders()->count(),
            'pendingOrders' => $user->orders()->where('status', 'pending')->count(),
            'processingOrders' => $user->orders()->where('status', 'proses')->count(),
            'completedOrders' => $user->orders()->where('status', 'selesai')->count(),
            'latestOrder' => $user->orders()->latest()->first(),
        ];
        return view('user.dashboard', $summary);
    })->name('dashboard');

    // -- Produk --
    Route::get('/products', [UserController::class, 'products'])->name('user.products');

    // -- Pesanan (Orders) --
    // Menampilkan halaman histori pesanan
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');

    // Menampilkan halaman desain & order
    Route::get('/orders/create/{customProduct}', [OrderController::class, 'createDesign'])->name('user.orders.create');

    // Menyimpan pesanan baru
    Route::post('/orders', [OrderController::class, 'store'])->name('user.orders.store');

    // Menampilkan detail satu pesanan
    Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('user.orders.show');

    // -- Lainnya --
    Route::get('/check-status', [UserController::class, 'checkStatus'])->name('user.check-status');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
});