<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini
use App\Models\CustomProduct;

class OrderController extends Controller
{
  /**
   * Tampilkan formulir pembuatan pesanan (desain).
   */
  // public function createDesign(Product $product)
  // {
  //     // Cek stok produk
  //     if ($product->stock <= 0) {
  //         return redirect()->route('user.products')->with('error', 'Produk ini sedang tidak tersedia.');
  //     }

  //     return view('user.design-and-order', compact('product'));
  // }

  // Ganti definisi metode ini
  public function createDesign(CustomProduct $customProduct)
  {
    return view('user.design-and-order', ['product' => $customProduct]);
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

  // Perbarui metode store
  // public function store(Request $request)
  // {
  //   // ... kode validasi lainnya ...

  //   $order = new Order();
  //   $order->user_id = Auth::id();
  //   // Cek apakah produknya adalah produk kustom
  //   if ($request->product_type === 'custom') { // Anda perlu menambahkan input hidden ini di form
  //     $order->custom_product_id = $request->product_id;
  //   } else {
  //     $order->product_id = $request->product_id;
  //   }
  //   // ... simpan data lainnya ...
  // }

  /**
   * Simpan pesanan baru.
   */
  public function store(Request $request)
  {
    // 1. Validasi data
    $request->validate([
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer|min:1',
      'size' => 'required|string',
      'selected_color' => 'required|string',
      'design_notes' => 'nullable|string',
      'total_price_input' => 'required|numeric',
      'design_data_url_depan' => 'nullable|string',
      'design_data_url_belakang' => 'nullable|string',
      'design_data_url_samping' => 'nullable|string',
    ]);

    // 2. Proses dan simpan gambar desain
    $designPaths = [];
    $views = ['depan', 'belakang', 'samping'];

    foreach ($views as $view) {
      $dataUrlInput = "design_data_url_{$view}";
      if ($request->has($dataUrlInput) && $request->input($dataUrlInput)) {
        $dataUrl = $request->input($dataUrlInput);

        // Ambil data gambar dari data URL
        list($type, $data) = explode(';', $dataUrl);
        list(, $data) = explode(',', $data);
        $imageData = base64_decode($data);

        // Tentukan nama file dan path
        $fileName = 'designs/' . Auth::id() . '_' . time() . "_{$view}.png";

        // Simpan file ke direktori storage
        Storage::disk('public')->put($fileName, $imageData);

        // Simpan path file untuk dimasukkan ke database
        $designPaths[$view] = $fileName;
      }
    }

    // 3. Buat dan simpan instance Order baru
    $order = new Order();
    $order->user_id = Auth::id();
    $order->product_id = $request->product_id;
    $order->quantity = $request->quantity;
    $order->size = $request->size;
    $order->color = $request->selected_color;
    $order->design_description = $request->design_notes;
    $order->total_price = $request->total_price_input;
    $order->status = 'pending';
    $order->design_file_depan = $designPaths['depan'] ?? null;
    $order->design_file_belakang = $designPaths['belakang'] ?? null;
    $order->design_file_samping = $designPaths['samping'] ?? null;

    $order->save();

    // 4. Kirim notifikasi ke admin via WhatsApp
    $this->sendWhatsAppNotification($order);

    // 5. Redirect ke halaman detail pesanan
    return redirect()->route('user.orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');
  }

  /**
   * Kirim notifikasi pesanan ke WhatsApp admin.
   */
  private function sendWhatsAppNotification(Order $order)
  {
    $adminPhoneNumber = '6281234567890'; // Ganti dengan nomor WhatsApp admin Anda
    $user = Auth::user();
    $product = Product::find($order->product_id);

    $message = "🎉 *PESANAN BARU!* 🎉\n\n";
    $message .= "*ID Pesanan*: #{$order->id}\n";
    $message .= "*Dari*: {$user->name}\n";
    $message .= "*Produk*: {$product->name}\n";
    $message .= "*Jumlah*: {$order->quantity} pcs\n";
    $message .= "*Ukuran*: {$order->size}\n";
    $message .= "*Warna*: {$order->color}\n";
    $message .= "*Total*: Rp " . number_format($order->total_price, 0, ',', '.') . "\n\n";
    $message .= "Silakan cek dashboard untuk detail lengkap dan hubungi user untuk pengiriman file desain.\n";
    $message .= "Link: " . route('user.orders.show', $order->id);

    try {
      $client = new Client();
      $client->post('https://api.your-whatsapp-provider.com/send', [
        'headers' => [
          'Authorization' => 'Bearer YOUR_API_TOKEN',
          'Content-Type' => 'application/json',
        ],
        'json' => [
          'to' => $adminPhoneNumber,
          'message' => $message,
        ],
      ]);
    } catch (\Exception $e) {
      Log::error('WhatsApp notification failed: ' . $e->getMessage());
    }
  }
}