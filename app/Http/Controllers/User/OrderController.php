<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CustomProduct;

class OrderController extends Controller
{
  // Menampilkan halaman desain & order
  public function createDesign(CustomProduct $customProduct)
  {
    if ($customProduct->stock <= 0) {
      return redirect()->route('user.products')->with('error', 'Produk ini sedang tidak tersedia.');
    }
    return view('user.design-and-order', ['product' => $customProduct]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'product_id' => 'required|exists:custom_products,id',
      'quantity' => 'required|integer|min:1',
      'design_notes' => 'nullable|string|max:1000',
      'size' => 'required|in:S,M,L,XL,XXL',
      'selected_color' => 'required|string',
      'total_price_input' => 'required|numeric',
    ]);

    $product = CustomProduct::findOrFail($request->product_id);

    if ($request->quantity > $product->stock) {
      return back()->withInput()->with('error', 'Jumlah pesanan (' . $request->quantity . ') melebihi stok yang tersedia (' . $product->stock . ').');
    }

    // ✅ Optimasi: Looping menggunakan helper (Kode jadi sangat pendek)
    $designPaths = [];
    $views = ['depan', 'belakang', 'samping'];

    foreach ($views as $view) {
      $inputName = 'design_data_url_' . $view;
      $designPaths[$inputName] = $this->saveDesignImage($request, $inputName, $view);
    }

    $order = Order::create([
      'user_id' => Auth::id(),
      'product_id' => $request->product_id,
      'quantity' => $request->quantity,
      'size' => $request->size,
      'color' => $request->selected_color,
      'design_description' => $request->design_notes,
      'notes' => $request->design_notes,
      'total_price' => $request->total_price_input,
      'status' => 'pending',
      'design_file_depan' => $designPaths['design_data_url_depan'] ?? null,
      'design_file_belakang' => $designPaths['design_data_url_belakang'] ?? null,
      'design_file_samping' => $designPaths['design_data_url_samping'] ?? null,
    ]);

    $product->decrement('stock', $request->quantity);

    return redirect()->route('user.orders.show', $order->id)
      ->with('success', 'Pesanan Anda berhasil dibuat!');
  }

  // ✅ Optimasi: Helper dirapikan agar parameter string lebih akurat
  private function saveDesignImage(Request $request, string $inputName, string $viewName): ?string
  {
    if (!$request->filled($inputName)) {
      return null;
    }

    $dataUrl = $request->input($inputName);
    @list($type, $data) = explode(';', $dataUrl);
    @list(, $data) = explode(',', $data);

    if ($data) {
      $imageData = base64_decode($data);
      $fileName = 'designs/' . uniqid() . '_' . $viewName . '.png';
      Storage::disk('public')->put($fileName, $imageData);
      return $fileName;
    }

    return null;
  }

  public function downloadDesign(Order $order)
  {
    // Ensure only admins can access
    if (!Auth::check() || Auth::user()->role !== 'admin') {
      abort(403, 'Unauthorized');
    }

    if (!$order->design_file) {
      return back()->with('error', 'File desain tidak ditemukan.');
    }

    $filePath = storage_path('app/public/' . $order->design_file);

    if (!file_exists($filePath)) {
      return back()->with('error', 'File desain tidak tersedia.');
    }

    $filename = 'desain_pesanan_' . $order->id . '_' . $order->user->name . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
    $filename = str_replace([' ', '/', '\\'], '_', $filename);

    return response()->download($filePath, $filename);
  }
}