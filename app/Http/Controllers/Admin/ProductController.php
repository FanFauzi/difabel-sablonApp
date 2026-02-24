<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.finishedProducts.index', compact('products'));
    }

    /**
     * Tampilkan formulir untuk membuat produk baru.
     */
    public function create()
    {
        return view('admin.finishedProducts.create');
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkProduct' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('finishedProducts', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'linkProduct' => $request->linkProduct
        ]);

        return redirect()->route('admin.finishedProducts.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail produk.
     */
    public function show(Product $product)
    {
        return view('admin.finishedProducts.show', compact('product'));
    }

    /**
     * Tampilkan formulir untuk mengedit produk.
     */
    public function edit(Product $product)
    {
        return view('admin.finishedProducts.edit', compact('product'));
    }

    /**
     * Perbarui produk di database.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'linkProduct' => 'nullable|string',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('finishedProducts', 'public');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'linkProduct' => $request->linkProduct
        ]);

        return redirect()->route('admin.finishedProducts.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk dari database.
     */

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public/finishedProducts')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.finishedProducts.index')->with('success', 'Produk berhasil dihapus!');
    }
}