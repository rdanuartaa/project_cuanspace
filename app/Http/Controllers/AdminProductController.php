<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kategori;
use App\Models\ProductDeletion;
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

    public function show($id)
    {
        $product = Product::with(['seller.user', 'kategori', 'deletion'])
            ->findOrFail($id);
        return view('admin.produk.show', compact('product'));
    }
    

   public function destroy(Request $request, $id)
{
    $request->validate([
        'alasan_penghapusan' => 'required|string|min:10|max:500'
    ]);

    try {
        $product = Product::findOrFail($id);
        $seller_id = $product->seller_id;
        
        // Simpan alasan penghapusan
        ProductDeletion::create([
            'product_id' => $product->id,
            'seller_id' => $seller_id,
            'deletion_reason' => $request->alasan_penghapusan,
            'deleted_by' => 'admin'
        ]);

        // Soft delete produk
        $product->delete();

        // Tambahkan flash session khusus untuk seller
        session()->put('seller_product_deleted', [
            'product_name' => $product->name,
            'deletion_reason' => $request->alasan_penghapusan
        ]);

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
}