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
        $customProducts = CustomProduct::all();
        return view('admin.products.index', compact('customProducts'));
    }

    /**
     * Tampilkan formulir untuk membuat produk kustom baru.
     */
    public function create()
    {
        return view('admin.products.create-custom');
    }

        public function show(CustomProduct $product)
    {
        return view('admin.products.show', compact('product'));
    }

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
            'small_design_cost' => $request->small_design_cost ?? 0,
            'medium_design_cost' => $request->medium_design_cost ?? 0,
            'large_design_cost' => $request->large_design_cost ?? 0,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.custom-products.index')->with('success', 'Produk kustom berhasil ditambahkan!');
    }

    /**
     * Tampilkan formulir untuk mengedit produk kustom.
     */
    public function edit(CustomProduct $customProduct)
    {
        return view('admin.products.edit-custom', compact('customProduct'));
    }

    /**
     * Perbarui produk kustom di database.
     */
    public function update(Request $request, CustomProduct $customProduct)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'small_design_cost' => 'nullable|numeric|min:0',
            'medium_design_cost' => 'nullable|numeric|min:0',
            'large_design_cost' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $customProduct->image;
        if ($request->hasFile('image')) {
            if ($customProduct->image && Storage::disk('public')->exists($customProduct->image)) {
                Storage::disk('public')->delete($customProduct->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $customProduct->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'small_design_cost' => $request->small_design_cost ?? 0,
            'medium_design_cost' => $request->medium_design_cost ?? 0,
            'large_design_cost' => $request->large_design_cost ?? 0,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.custom-products.index')->with('success', 'Produk kustom berhasil diperbarui!');
    }

    /**
     * Hapus produk kustom dari database.
     */
    public function destroy(CustomProduct $customProduct)
    {
        if ($customProduct->image && Storage::disk('public')->exists($customProduct->image)) {
            Storage::disk('public')->delete($customProduct->image);
        }

        $customProduct->delete();

        return redirect()->route('admin.custom-products.index')->with('success', 'Produk kustom berhasil dihapus!');
    }
}