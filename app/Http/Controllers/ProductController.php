<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Check if the user is a seller
     */
    private function checkSeller()
    {
        if (!Auth::check() || !Auth::user()->seller) {
            abort(403, 'Anda perlu mendaftar sebagai seller terlebih dahulu.');
        }
    }

    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $this->checkSeller();

        try {
            $query = Product::where('seller_id', Auth::user()->seller->id);

            // Apply kategori filter if set
            if ($request->has('kategori') && $request->kategori) {
                $query->where('kategori_id', $request->kategori);
            }

            // Apply status filter if set
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            $products = $query->latest()->paginate(10);
            $kategoris = Kategori::all();

            return view('seller.produk.index', compact('products', 'kategoris'));
        } catch (\Exception $e) {
            Log::error('Error pada method index: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $this->checkSeller();

        try {
            $kategoris = Kategori::all();

            if ($kategoris->isEmpty()) {
                return redirect()->route('seller.produk')
                    ->with('error', 'Tidak ada kategori tersedia. Hubungi admin untuk menambahkan kategori.');
            }

            return view('seller.produk.create', compact('kategoris'));
        } catch (\Exception $e) {
            Log::error('Error pada method create: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $this->checkSeller();

        try {
            // Validasi input
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'kategori_id' => 'required|exists:kategoris,id',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'digital_file' => 'required|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
                'status' => 'required|in:draft,published,archived',
            ]);

            DB::beginTransaction();

            // Validasi file
            if (!$request->hasFile('thumbnail')) {
                throw new \Exception('File thumbnail tidak ditemukan');
            }

            if (!$request->hasFile('digital_file')) {
                throw new \Exception('File digital tidak ditemukan');
            }

            // Generate unique filenames
            $thumbnailName = Str::uuid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $digitalFileName = Str::uuid() . '.' . $request->file('digital_file')->getClientOriginalExtension();

            // Store files
            $thumbnailPath = $request->file('thumbnail')->storeAs('thumbnails', $thumbnailName, 'public');
            $digitalFilePath = $request->file('digital_file')->storeAs('digital_files', $digitalFileName, 'public');

            // Log file storage details
            Log::info('File upload details', [
                'thumbnail_path' => $thumbnailPath,
                'digital_file_path' => $digitalFilePath,
                'thumbnail_name' => $thumbnailName,
                'digital_file_name' => $digitalFileName
            ]);

            // Create product
            $product = new Product();
            $product->seller_id = Auth::user()->seller->id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->kategori_id = $request->kategori_id;
            $product->thumbnail = $thumbnailName; // Simpan nama file saja
            $product->digital_file = $digitalFileName; // Simpan nama file saja
            $product->status = $request->status;

            $product->save();

            DB::commit();

            return redirect()->route('seller.produk')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            // Log validation errors
            Log::error('Validation errors', [
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Gagal menambahkan produk: Periksa kembali input Anda');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error terperinci
            Log::error('Gagal menyimpan produk', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $this->checkSeller();

        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);
            $kategoris = Kategori::all();

            return view('seller.produk.edit', compact('product', 'kategoris'));
        } catch (\Exception $e) {
            Log::error('Error pada method edit: ' . $e->getMessage());
            return redirect()->route('seller.produk')
                ->with('error', 'Produk tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $this->checkSeller();

        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'kategori_id' => 'required|exists:kategoris,id',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'digital_file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
                'status' => 'required|in:draft,published,archived',
            ]);

            DB::beginTransaction();

            // Update data produk
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->kategori_id = $request->kategori_id;
            $product->status = $request->status;

            // Handle thumbnail update
            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($product->thumbnail) {
                    Storage::disk('public')->delete('thumbnails/' . $product->thumbnail);
                }

                // Generate unique filename
                $thumbnailName = Str::uuid() . '.' . $request->file('thumbnail')->getClientOriginalExtension();
                
                // Simpan thumbnail baru
                $request->file('thumbnail')->storeAs('thumbnails', $thumbnailName, 'public');
                
                // Update nama file thumbnail
                $product->thumbnail = $thumbnailName;
            }

            // Handle digital file update
            if ($request->hasFile('digital_file')) {
                // Hapus file digital lama jika ada
                if ($product->digital_file) {
                    Storage::disk('public')->delete('digital_files/' . $product->digital_file);
                }

                // Generate unique filename
                $digitalFileName = Str::uuid() . '.' . $request->file('digital_file')->getClientOriginalExtension();
                
                // Simpan file digital baru
                $request->file('digital_file')->storeAs('digital_files', $digitalFileName, 'public');
                
                // Update nama file digital
                $product->digital_file = $digitalFileName;
            }

            $product->save();

            DB::commit();

            return redirect()->route('seller.produk')
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui produk', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $this->checkSeller();

        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);

            DB::beginTransaction();

            // Hapus thumbnail
            if ($product->thumbnail) {
                Storage::disk('public')->delete('thumbnails/' . $product->thumbnail);
            }

            // Hapus file digital
            if ($product->digital_file) {
                Storage::disk('public')->delete('digital_files/' . $product->digital_file);
            }

            // Hapus produk
            $product->delete();

            DB::commit();

            return redirect()->route('seller.produk')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus produk: ' . $e->getMessage());
            
            return redirect()->route('seller.produk')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Display the dashboard for products.
     */
    public function dashboard()
    {
        $this->checkSeller();

        try {
            $products = Product::where('seller_id', Auth::user()->seller->id)->get();
            $productCount = $products->count();
            $publishedCount = $products->where('status', 'published')->count();
            $draftCount = $products->where('status', 'draft')->count();
            $archivedCount = $products->where('status', 'archived')->count();

            // Get recent products
            $recentProducts = Product::where('seller_id', Auth::user()->seller->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('seller.produk.dashboard', compact(
                'productCount',
                'publishedCount',
                'draftCount',
                'archivedCount',
                'recentProducts'
            ));
        } catch (\Exception $e) {
            Log::error('Error pada method dashboard: ' . $e->getMessage());
            return redirect()->route('seller.produk')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}