<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CustomProduct;
use App\Models\Order;
use App\Models\User;

class UserController extends Controller
{

    public function products()
    {
        $products = CustomProduct::all();
        return view('user.products', compact('products'));
    }

    public function orders()
    {
        /** @var User $user */
        $user = Auth::user();
        $orders = $user->orders()->latest()->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('user.order-show', compact('order'));
    }

    public function checkStatus(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $order = null;
        $searchPerformed = false;

        // If order ID is provided, search for it
        if ($request->has('order_id') && $request->order_id) {
            $order = Order::where('id', $request->order_id)
                         ->where('user_id', $user->id)
                         ->first();
            $searchPerformed = true;
        }

        // Get user's recent orders for quick access
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('user.check-status', compact('order', 'recentOrders', 'searchPerformed'));
    }

    public function createOrder(CustomProduct $product)
    {
        // Check if product is in stock
        if ($product->stock <= 0) {
            return redirect()->route('user.products')->with('error', 'Produk ini sedang tidak tersedia.');
        }

        return view('user.order-create', compact('product'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'design_description' => 'required|string|max:1000',
            'design_file' => 'required|file|mimes:jpeg,jpg,png,pdf,doc,docx|max:5120',
            'design_size' => 'required|in:small,medium,large', // Tambahan: validasi ukuran desain
            'notes' => 'nullable|string|max:500',
        ]);

        $product = CustomProduct::findOrFail($request->product_id);

        // Check stock availability
        if ($request->quantity > $product->stock) {
            return back()->withErrors(['quantity' => 'Jumlah pesanan melebihi stok yang tersedia.']);
        }

        // Handle file upload
        $designFilePath = null;
        if ($request->hasFile('design_file')) {
            $designFilePath = $request->file('design_file')->store('designs', 'public');
        }

        // Calculate design cost based on selected size
        $designCost = 0;
        switch ($request->design_size) {
            case 'small':
                $designCost = $product->small_design_cost;
                break;
            case 'medium':
                $designCost = $product->medium_design_cost;
                break;
            case 'large':
                $designCost = $product->large_design_cost;
                break;
        }

        // Calculate total price
        $totalPrice = ($product->price * $request->quantity) + $designCost;

        // Create order
        Order::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'design_description' => $request->design_description,
            'design_file' => $designFilePath,
            'design_size' => $request->design_size, // Simpan ukuran desain
            'design_cost' => $designCost, // Simpan biaya desain
            'notes' => $request->notes,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('user.orders')->with('success', 'Pesanan berhasil dibuat! Kami akan segera memproses pesanan Anda.');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Check current password if user wants to change password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok']);
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}