<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\Admin\CustomProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderManageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/force-logout', [AuthController::class, 'forceLogout'])->name('force.logout');

// ================= ADMIN ROUTES =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/home', [HomeController::class, 'adminHome'])->name('home');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Produk Kustom
    Route::resource('products', CustomProductController::class);
    Route::get('/products/{id}/delete', [CustomProductController::class, 'delete'])->name('products.delete');

    // User Management
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('/users/{id}/delete', [AdminUserController::class, 'delete'])->name('users.delete');

    // Order Management
    Route::resource('orders', OrderManageController::class)->except(['create', 'store']);
    Route::get('/orders/{id}/delete', [OrderManageController::class, 'delete'])->name('orders.delete');
    
    // Order Controller (bawaan)
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}/download-design', [OrderController::class, 'downloadDesign'])->name('orders.download-design');

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
});


// ================= USER ROUTES =================
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/home', [HomeController::class, 'userHome'])->name('user.home');
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard'); // Berubah dari 'dashboard' menjadi 'user.dashboard'

    // -- Produk --
    Route::get('/products', [UserController::class, 'products'])->name('user.products');

    // -- Pesanan (Orders) --
    Route::get('/orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/orders/create/{customProduct}', [OrderController::class, 'createDesign'])->name('user.orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('user.orders.store');
    Route::get('/orders/{order}', [UserController::class, 'showOrder'])->name('user.orders.show');

    // -- Lainnya --
    Route::get('/check-status', [UserController::class, 'checkStatus'])->name('user.check-status');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
});