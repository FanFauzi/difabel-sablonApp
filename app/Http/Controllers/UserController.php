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

    // public function createOrder(CustomProduct $product)
    // {
    //     if ($product->stock <= 0) {
    //         return redirect()->route('user.products')->with('error', 'Maaf, produk ini sedang habis.');
    //     }
    //     return view('user.design-and-order', compact('product'));
    // }

    // public function storeOrder(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:custom_products,id',
    //         'quantity' => 'required|integer|min:1',
    //         'design_notes' => 'nullable|string|max:1000',
    //         'size' => 'required|in:S,M,L,XL,XXL',
    //         'selected_color' => 'required|string',
    //         'total_price_input' => 'required|numeric',
    //     ]);

    //     $product = CustomProduct::findOrFail($request->product_id);

    //     if ($request->quantity > $product->stock) {
    //         return back()->withInput()->with('error', 'Jumlah pesanan (' . $request->quantity . ') melebihi stok yang tersedia (' . $product->stock . ').');
    //     }

    //     $designPaths = [];
    //     $views = ['depan', 'belakang', 'samping'];
    //     foreach ($views as $view) {
    //         $inputName = 'design_data_url_' . $view;
    //         if ($request->filled($inputName)) {
    //             $dataUrl = $request->input($inputName);
    //             list($type, $data) = explode(';', $dataUrl);
    //             list(, $data) = explode(',', $data);
    //             $imageData = base64_decode($data);

    //             $fileName = 'designs/' . uniqid() . '_' . $view . '.png';
    //             \Storage::disk('public')->put($fileName, $imageData);
    //             $designPaths[$inputName] = $fileName;
    //         }
    //     }

    //     Order::create([
    //         'user_id' => Auth::id(),
    //         'product_id' => $request->product_id,
    //         'quantity' => $request->quantity,
    //         'size' => $request->size,
    //         'color' => $request->selected_color,
    //         'design_description' => $request->design_notes,
    //         'notes' => $request->design_notes,
    //         'total_price' => $request->total_price_input,
    //         'status' => 'pending',
    //         'design_file_depan' => $designPaths['design_data_url_depan'] ?? null,
    //         'design_file_belakang' => $designPaths['design_data_url_belakang'] ?? null,
    //         'design_file_samping' => $designPaths['design_data_url_samping'] ?? null,
    //     ]);

    //     // PERBAIKAN: Kurangi stok di sini, HANYA SEKALI setelah pesanan berhasil dibuat
    //     $product->decrement('stock', $request->quantity);

    //     return redirect()->route('user.orders')->with('success', 'Pesanan berhasil dibuat! Kami akan segera memproses pesanan Anda.');
    // }

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