<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomProductController extends Controller
{
    /**
     * Tampilkan daftar produk kustom.
     */
    public function index()
    {
        $customProducts = CustomProduct::latest()->get();
        return view('admin.products.index', compact('customProducts'));
    }

    /**
     * Tampilkan formulir untuk membuat produk kustom baru.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    // public function show(CustomProduct $product)
    // {
    //     return view('admin.products.show', compact('product'));
    // }

    /**
     * Simpan produk kustom baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0', // Penambahan validasi stok
            'small_design_cost' => 'nullable|numeric|min:0',
            'medium_design_cost' => 'nullable|numeric|min:0',
            'large_design_cost' => 'nullable|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (CustomProduct::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        CustomProduct::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock, // Penambahan data stok
            'small_design_cost' => $request->small_design_cost ?? 0,
            'medium_design_cost' => $request->medium_design_cost ?? 0,
            'large_design_cost' => $request->large_design_cost ?? 0,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk kustom berhasil ditambahkan!');
    }

    /**
     * Tampilkan formulir untuk mengedit produk kustom.
     */
    public function edit(CustomProduct $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Perbarui produk kustom di database.
     */
    public function update(Request $request, CustomProduct $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0', // Penambahan validasi stok
            'small_design_cost' => 'nullable|numeric|min:0',
            'medium_design_cost' => 'nullable|numeric|min:0',
            'large_design_cost' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock, // Penambahan data stok
            'small_design_cost' => $request->small_design_cost ?? 0,
            'medium_design_cost' => $request->medium_design_cost ?? 0,
            'large_design_cost' => $request->large_design_cost ?? 0,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk kustom berhasil diperbarui!');
    }

    /**
     * Hapus produk kustom dari database.
     */

    public function delete($id)
    {
        $product = CustomProduct::findOrFail($id);
        return view('admin.products.delete', compact('product'));
    }


    public function destroy(CustomProduct $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}