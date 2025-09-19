<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin.
     */
    public function dashboard()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'selesai')->count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact('totalOrders', 'pendingOrders', 'completedOrders', 'totalUsers'));
    }

    public function showProducts()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Tampilkan form untuk membuat produk baru.
     */
    public function createProduct()
    {
        return view('admin.products.create-custom');
    }

    /**
     * Simpan produk baru.
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'small_design_cost' => 'required|numeric|min:0',
            'medium_design_cost' => 'required|numeric|min:0',
            'large_design_cost' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'small_design_cost' => $request->small_design_cost,
            'medium_design_cost' => $request->medium_design_cost,
            'large_design_cost' => $request->large_design_cost,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit produk.
     */
    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Perbarui produk.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'small_design_cost' => 'required|numeric|min:0',
            'medium_design_cost' => 'required|numeric|min:0',
            'large_design_cost' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productData = $request->except('image');

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        $product->update($productData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }
    
    /**
     * Tampilkan konfirmasi hapus produk.
     */
    public function deleteProduct(Product $product)
    {
        return view('admin.products.delete', compact('product'));
    }

    /**
     * Hapus produk.
     */
    public function destroyProduct(Product $product)
    {
        // Hapus gambar produk
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Tampilkan daftar pesanan.
     */
    public function showOrders()
    {
        $orders = Order::with('user', 'product')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan.
     */
    public function showOrder(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Tampilkan form untuk mengedit status pesanan.
     */
    public function editOrder(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Perbarui status pesanan.
     */
    public function updateOrder(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai,ditolak',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Hapus pesanan.
     */
    public function deleteOrder(Order $order)
    {
        return view('admin.orders.delete', compact('order'));
    }

    /**
     * Hapus pesanan dari database.
     */
    public function destroyOrder(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}