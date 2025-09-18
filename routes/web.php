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

// Route::get('/', function () {
//     if (Auth::check()) {
//         $user = Auth::user();
//         if ($user->role === 'admin') {
//             return redirect()->route('admin.dashboard');
//         } elseif ($user->role === 'user') {
//             return redirect('/user/dashboard');
//         }
//     }
//     return redirect('/login');
// });

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
// Route::middleware(['auth', 'role:admin'])->group(function () {

//     // Rute untuk manajemen produk standar
//     Route::resource('products', ProductController::class);

//     // Rute baru untuk manajemen produk kustom
//     Route::resource('custom-products', CustomProductController::class);
//     // Dashboard
//     Route::get('/admin/dashboard', function () {
//         return view('dashboard');
//     })->name('admin.dashboard');

//     // User Management
//     Route::get('/admin/users', function () {
//         $users = \App\Models\User::all();
//         return view('admin.users.index', compact('users'));
//     })->name('users.index');

//     Route::get('/admin/users/create', function () {
//         return view('admin.users.create');
//     })->name('users.create');

//     Route::post('/admin/users', function (Illuminate\Http\Request $request) {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8|confirmed',
//             'role' => 'required|in:user,admin',
//         ]);

//         \App\Models\User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => bcrypt($request->password),
//             'role' => $request->role,
//         ]);

//         return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
//     })->name('users.store');

//     Route::get('/admin/users/{id}/edit', function ($id) {
//         $user = \App\Models\User::findOrFail($id);
//         return view('admin.users.edit', compact('user'));
//     })->name('users.edit');

//     Route::put('/admin/users/{id}', function (Illuminate\Http\Request $request, $id) {
//         $user = \App\Models\User::findOrFail($id);

//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users,email,' . $id,
//             'role' => 'required|in:user,admin',
//             'password' => 'nullable|string|min:8|confirmed',
//         ]);

//         $user->update([
//             'name' => $request->name,
//             'email' => $request->email,
//             'role' => $request->role,
//         ]);

//         if ($request->filled('password')) {
//             $user->update(['password' => bcrypt($request->password)]);
//         }

//         return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
//     })->name('users.update');

//     Route::get('/admin/users/{id}/delete', function ($id) {
//         $user = \App\Models\User::findOrFail($id);
//         return view('admin.users.delete', compact('user'));
//     })->name('users.delete');

//     Route::delete('/admin/users/{id}', function ($id) {
//         $user = \App\Models\User::findOrFail($id);
//         if ($user->id === Auth::id()) {
//             return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus user yang sedang login!');
//         }
//         $user->delete();
//         return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
//     })->name('users.destroy');

//     // Product Management
//     Route::get('/admin/products', function () {
//         $products = \App\Models\Product::all();
//         return view('admin.products.index', compact('products'));
//     })->name('products.index');

//     Route::get('/admin/products/create', function () {
//         return view('admin.products.create');
//     })->name('products.create');

//     Route::post('/admin/products', function (Illuminate\Http\Request $request) {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'price' => 'required|numeric|min:0',
//             'description' => 'nullable|string',
//             'stock' => 'required|integer|min:0',
//             'category' => 'required|string',
//             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         ]);

//         $imagePath = null;
//         if ($request->hasFile('image')) {
//             $imagePath = $request->file('image')->store('products', 'public');
//         }

//         \App\Models\Product::create([
//             'name' => $request->name,
//             'price' => $request->price,
//             'description' => $request->description,
//             'stock' => $request->stock,
//             'category' => $request->category,
//             'image' => $imagePath,
//         ]);

//         return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
//     })->name('products.store');

//     Route::get('/admin/products/{id}/edit', function ($id) {
//         $product = \App\Models\Product::findOrFail($id);
//         return view('admin.products.edit', compact('product'));
//     })->name('products.edit');

//     Route::put('/admin/products/{id}', function (Illuminate\Http\Request $request, $id) {
//         $product = \App\Models\Product::findOrFail($id);

//         $request->validate([
//             'name' => 'required|string|max:255',
//             'price' => 'required|numeric|min:0',
//             'description' => 'nullable|string',
//             'stock' => 'required|integer|min:0',
//             'category' => 'required|string',
//             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         ]);

//         $imagePath = $product->image;
//         if ($request->hasFile('image')) {
//             if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image)) {
//                 \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
//             }
//             $imagePath = $request->file('image')->store('products', 'public');
//         }

//         $product->update([
//             'name' => $request->name,
//             'price' => $request->price,
//             'description' => $request->description,
//             'stock' => $request->stock,
//             'category' => $request->category,
//             'image' => $imagePath,
//         ]);

//         return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
//     })->name('products.update');

//     Route::get('/admin/products/{id}/delete', function ($id) {
//         $product = \App\Models\Product::findOrFail($id);
//         return view('admin.products.delete', compact('product'));
//     })->name('products.delete');

//     Route::delete('/admin/products/{id}', function ($id) {
//         $product = \App\Models\Product::findOrFail($id);
//         $product->delete();
//         return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
//     })->name('products.destroy');

//     // Order Management
//     Route::get('/admin/orders', function () {
//         $orders = \App\Models\Order::with(['user', 'orderItems.product'])->latest()->get();
//         return view('admin.orders.index', compact('orders'));
//     })->name('orders.index');

//     Route::get('/admin/orders/{id}', function ($id) {
//         $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
//         return view('admin.orders.show', compact('order'));
//     })->name('orders.show');

//     Route::get('/admin/orders/{id}/edit', function ($id) {
//         $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
//         return view('admin.orders.edit', compact('order'));
//     })->name('orders.edit');

//     Route::put('/admin/orders/{id}', function (Illuminate\Http\Request $request, $id) {
//         $order = \App\Models\Order::findOrFail($id);

//         $request->validate([
//             'status' => 'required|in:pending,proses,selesai,ditolak',
//             'notes' => 'nullable|string',
//         ]);

//         $order->update([
//             'status' => $request->status,
//             'notes' => $request->notes,
//         ]);

//         return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diperbarui!');
//     })->name('orders.update');

//     Route::get('/admin/orders/{id}/delete', function ($id) {
//         $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
//         return view('admin.orders.delete', compact('order'));
//     })->name('orders.delete');

//     Route::delete('/admin/orders/{id}', function ($id) {
//         $order = \App\Models\Order::findOrFail($id);
//         $order->delete();
//         return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus!');
//     })->name('orders.destroy');

//     // Profile
//     Route::get('/admin/profile', [AuthController::class, 'showProfile'])->name('admin.profile');
//     Route::put('/admin/profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');

//     // Activity Logs
//     Route::prefix('admin')->name('activity-logs.')->group(function () {
//         Route::get('/activity-logs', [ActivityLogsController::class, 'index'])->name('index');
//         Route::get('/activity-logs/{id}', [ActivityLogsController::class, 'show'])->name('show');
//         Route::delete('/activity-logs/{id}', [ActivityLogsController::class, 'destroy'])->name('destroy');
//         Route::delete('/activity-logs', [ActivityLogsController::class, 'clear'])->name('clear');
//     });

//     // Financial Reports
//     Route::get('/admin/reports/financial', [FinancialReportController::class, 'index'])->name('reports.financial');
//     Route::get('/admin/reports/financial/export/excel', [FinancialReportController::class, 'exportExcel'])->name('reports.financial.export.excel');
//     Route::get('/admin/reports/financial/export/pdf', [FinancialReportController::class, 'exportPDF'])->name('reports.financial.export.pdf');

//     // Design File Downloads
//     Route::get('/admin/orders/{order}/download-design', function (\App\Models\Order $order) {
//         // Ensure only admins can access
//         if (!Auth::check() || Auth::user()->role !== 'admin') {
//             abort(403, 'Unauthorized');
//         }

//         // Check if order belongs to authenticated admin's management
//         if (!$order->design_file) {
//             return back()->with('error', 'File desain tidak ditemukan.');
//         }

//         $filePath = storage_path('app/public/' . $order->design_file);

//         if (!file_exists($filePath)) {
//             return back()->with('error', 'File desain tidak tersedia.');
//         }

//         // Get original filename or create one
//         $originalName = basename($order->design_file);
//         $extension = pathinfo($filePath, PATHINFO_EXTENSION);

//         // Create a meaningful filename
//         $filename = 'desain_pesanan_' . $order->id . '_' . $order->user->name . '.' . $extension;
//         $filename = str_replace([' ', '/', '\\'], '_', $filename); // Replace problematic characters

//         return response()->download($filePath, $filename);
//     })->name('orders.download-design');
// });

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Rute untuk manajemen produk standar
    // Route::resource('products', ProductController::class);
    Route::resource('products', CustomProductController::class);

    // Rute baru untuk manajemen produk kustom
    // Route::resource('custom-products', CustomProductController::class);

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
        return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diperbarui!');
    })->name('orders.update');
    Route::get('/orders/{id}/delete', function ($id) {
        $order = \App\Models\Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.delete', compact('order'));
    })->name('orders.delete');
    Route::delete('/orders/{id}', function ($id) {
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus!');
    })->name('orders.destroy');

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
    // Route::get('/reports/financial', [FinancialReportController::class, 'index'])->name('reports.financial');
    // Route::get('/reports/financial/export/excel', [FinancialReportController::class, 'exportExcel'])->name('reports.financial.export.excel');
    // Route::get('/reports/financial/export/pdf', [FinancialReportController::class, 'exportPDF'])->name('reports.financial.export.pdf');

    // // Design File Downloads
    // Route::get('/orders/{order}/download-design', function (\App\Models\Order $order) {
    //     // Ensure only admins can access
    //     if (!Auth::check() || Auth::user()->role !== 'admin') {
    //         abort(403, 'Unauthorized');
    //     }

    //     if (!$order->design_file) {
    //         return back()->with('error', 'File desain tidak ditemukan.');
    //     }

    //     $filePath = storage_path('app/public/' . $order->design_file);

    //     if (!file_exists($filePath)) {
    //         return back()->with('error', 'File desain tidak tersedia.');
    //     }

    //     $originalName = basename($order->design_file);
    //     $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    //     $filename = 'desain_pesanan_' . $order->id . '_' . $order->user->name . '.' . $extension;
    //     $filename = str_replace([' ', '/', '\\'], '_', $filename);

    //     return response()->download($filePath, $filename);
    // })->name('orders.download-design');

    // Financial Reports
    Route::get('/reports/financial', [FinancialReportController::class, 'index'])->name('reports.financial');
    Route::get('/reports/financial/export/excel', [FinancialReportController::class, 'exportExcel'])->name('reports.financial.export.excel');
    Route::get('/reports/financial/export/pdf', [FinancialReportController::class, 'exportPDF'])->name('reports.financial.export.pdf');


    // Design File Downloads
    Route::get('/orders/{order}/download-design', [OrderController::class, 'downloadDesign'])->name('orders.download-design');

});


// User Routes - Only for Regular Users
// Route::middleware(['auth', 'role:user'])->group(function () {

//     // Home
//     Route::get('/user/home', function () {
//         return view('home');
//     })->name('home');

//     // // Custom Design
//     // Route::get('/user/custom-design', function () {
//     //     return view('customDesign');
//     // })->name('customDesign');

//     // Products
//     Route::get('/products', [UserController::class, 'products'])->name('user.products');

//     // ✅ Rute untuk desain dan pemesanan
//     Route::get('/orders/design/{product}', [OrderController::class, 'createDesign'])->name('user.orders.design');
//     // Route::post('/orders', [OrderController::class, 'store'])->name('user.orders.store');
//     Route::post('/orders', [OrderController::class, 'store'])->name('user.orders.store');

//     // Orders
//     // Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
//     // // ✅ Gunakan OrderController untuk menyimpan pesanan
//     // Route::post('/orders', [OrderController::class, 'store'])->name('user.orders.store');
//     // Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('user.orders.show');


//     // User Dashboard
//     Route::get('/user/dashboard', function () {
//         /** @var \App\Models\User $user */
//         $user = Auth::user();
//         $totalOrders = $user->orders()->count();
//         $pendingOrders = $user->orders()->where('status', 'pending')->count();
//         $processingOrders = $user->orders()->where('status', 'proses')->count();
//         $completedOrders = $user->orders()->where('status', 'selesai')->count();
//         $latestOrder = $user->orders()->latest()->first();

//         return view('user.dashboard', compact('totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders', 'latestOrder'));
//     })->name('user.dashboard');

//     // Products
//     Route::get('/products', [UserController::class, 'products'])->name('user.products');

//     // Orders
//     Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
//     Route::get('/orders/create/{product}', [UserController::class, 'createOrder'])->name('user.orders.create');
//     Route::post('/orders', [UserController::class, 'storeOrder'])->name('user.orders.store');
//     Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('user.orders.show');

//     // Order Status Checking
//     Route::get('/check-status', [UserController::class, 'checkStatus'])->name('user.check-status');

//     // Profile
//     Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
//     Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
// });


Route::middleware(['auth', 'role:user'])->group(function () {

    // Home
    Route::get('/user/home', function () {
        return view('home');
    })->name('home');

    // Products
    Route::get('/products', [UserController::class, 'products'])->name('user.products');

    // Orders
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('user.orders.show');

    // 👕 Rute untuk Desain & Pemesanan Produk 
    // Menggunakan satu rute untuk proses pembuatan pesanan
    Route::get('/orders/create/{product}', [OrderController::class, 'createDesign'])->name('user.orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('user.orders.store');

    // User Dashboard
    Route::get('/user/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $processingOrders = $user->orders()->where('status', 'proses')->count();
        $completedOrders = $user->orders()->where('status', 'selesai')->count();
        $latestOrder = $user->orders()->latest()->first();

        return view('user.dashboard', compact('totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders', 'latestOrder'));
    })->name('user.dashboard');

    // Order Status Checking
    Route::get('/check-status', [UserController::class, 'checkStatus'])->name('user.check-status');

    // Profile
    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
});