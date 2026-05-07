<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CustomProduct;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  // Menampilkan halaman desain & order
  public function createDesign(CustomProduct $customProduct)
  {
    if ($customProduct->stock <= 0) {
      return redirect()->route('user.products')->with('error', 'Produk ini sedang tidak tersedia.');
    }
    return view('user.orders.design-and-order', ['product' => $customProduct]);
  }

  public function store(Request $request)
  {
    // 1. Validasi Input (Pastikan semua field ada)
    $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1',
      'design_notes' => 'nullable|string|max:1000',
      'size' => 'required|in:S,M,L,XL,XXL',
      'selected_color' => 'required|string',
      'total_price_input' => 'required|numeric|min:1000', // Minimal harga masuk akal
    ]);

    $product = CustomProduct::findOrFail($request->product_id);

    // 2. Cek Stok
    if ($request->quantity > $product->stock) {
      return back()->withInput()->with('error', 'Stok tidak mencukupi (Tersedia: ' . $product->stock . ')');
    }

    try {
      DB::beginTransaction();

      $designPaths = [];
      $rawLinks = [];
      $views = ['depan', 'belakang', 'samping'];

      foreach ($views as $view) {
        // Simpan Mockup (Preview)
        if ($request->filled('design_data_url_' . $view)) {
          $designPaths[$view] = $this->saveDesignImage($request->input('design_data_url_' . $view), $view . '_mockup');
        }

        // Simpan Bahan Cetak (Hanya Desain)
        if ($request->filled('raw_design_data_url_' . $view)) {
          $rawPath = $this->saveDesignImage($request->input('raw_design_data_url_' . $view), $view . '_BAHAN_CETAK');
          if ($rawPath) {
            $rawLinks[] = "BAHAN " . strtoupper($view) . ": " . asset('storage/' . $rawPath);
          }
        }
      }

      // Tambahkan Link ke Notes agar Admin gampang download
      $adminNotes = $request->design_notes . "\n\n--- DOWNLOAD BAHAN CETAK HD ---\n" . implode("\n", $rawLinks);

      $orderNumber = 'ORD-' . strtoupper(uniqid());

      $order = Order::create([
        'user_id' => Auth::id(),
        'order_number' => $orderNumber,
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'size' => $request->size,
        'color' => $request->selected_color,
        'notes' => $adminNotes, // Masuk ke kolom notes
        'total_price' => $request->total_price_input,
        'status' => 'pending',
        'design_file_depan' => $designPaths['depan'] ?? null,
        'design_file_belakang' => $designPaths['belakang'] ?? null,
        'design_file_samping' => $designPaths['samping'] ?? null,
      ]);

      $product->decrement('stock', $request->quantity);
      DB::commit();

      return redirect()->route('user.orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat! Silahkan konfirmasi ke Admin.');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->with('error', $e->getMessage());
    }
  }

  public function show($id)
  {
    $order = Order::with('product')->findOrFail($id);
    return view('user.orders.show', compact('order'));
  }

  // Helper saveDesignImage diperbaiki agar lebih stabil
  private function saveDesignImage($dataUrl, $viewName)
  {
    if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
      $data = substr($dataUrl, strpos($dataUrl, ',') + 1);
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