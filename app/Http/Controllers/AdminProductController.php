<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk produk
        $query = Product::with(['seller.user', 'kategori']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Ambil data produk
        $products = $query->latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('admin.produk.index', compact('products', 'kategoris'));
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'alasan_penghapusan' => 'required|string|min:10|max:500'
        ], [
            'alasan_penghapusan.required' => 'Alasan penghapusan harus diisi',
            'alasan_penghapusan.min' => 'Alasan penghapusan minimal 10 karakter',
            'alasan_penghapusan.max' => 'Alasan penghapusan maksimal 500 karakter'
        ]);

        try {
            $product = Product::findOrFail($id);

            // Hapus file terkait
            if ($product->thumbnail) {
                Storage::disk('public')->delete('thumbnails/' . $product->thumbnail);
            }

            if ($product->digital_file) {
                Storage::disk('public')->delete('digital_files/' . $product->digital_file);
            }

            // Log alasan penghapusan
            Log::info('Produk dihapus oleh admin', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'seller_id' => $product->seller_id,
                'alasan_penghapusan' => $request->alasan_penghapusan
            ]);

            // Hapus produk
            $product->delete();

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus produk', [
                'error' => $e->getMessage(),
                'product_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $product = Product::with(['seller.user', 'kategori'])->findOrFail($id);
        return view('admin.produk.show', compact('product'));
    }
}