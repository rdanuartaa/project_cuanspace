<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Debug image path
     */
    private function debugImagePath($path) {
        $fullPath = public_path('storage/' . $path);
        $exists = file_exists($fullPath);
        
        Log::info('Debug Image Path:');
        Log::info('- Database path: ' . $path);
        Log::info('- Full path: ' . $fullPath);
        Log::info('- File exists: ' . ($exists ? 'Yes' : 'No'));
        Log::info('- Public URL: ' . asset('storage/' . $path));
        
        return $exists;
    }

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
     * Check if storage link exists
     */
    private function checkStorageLink()
    {
        if (!File::exists(public_path('storage'))) {
            // Jika symlink tidak ada, buat pesan warning tapi jangan buat error fatal
            Log::warning('Storage symlink not found. Run "php artisan storage:link"');
        }
    }

    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $this->checkSeller();
        $this->checkStorageLink();
        
        try {
            $query = Product::query();
            
            // Filter produk berdasarkan seller
            $query->where('seller_id', Auth::user()->seller->id);
            
            // Filter berdasarkan kategori jika ada
            if ($request->has('kategori') && $request->kategori) {
                $query->where('kategori_id', $request->kategori);
            }
            
            // Filter berdasarkan status jika ada
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
        $this->checkStorageLink();
        
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
        $this->checkStorageLink();
        
        // Log untuk debugging
        Log::info('Method store dipanggil');
        Log::info('Request data:', $request->all());
        
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
            
            Log::info('Validasi berhasil');

            DB::beginTransaction();
            
            // Pastikan seller_id ada
            if (!Auth::user()->seller->id) {
                throw new \Exception('ID seller tidak ditemukan');
            }
            
            // Upload thumbnail ke folder public
            if (!$request->hasFile('thumbnail')) {
                throw new \Exception('File thumbnail tidak ditemukan');
            }
            
            // Simpan file thumbnail langsung ke public/storage folder
            $thumbnailFile = $request->file('thumbnail');
            $thumbnailName = time() . '_' . $thumbnailFile->getClientOriginalName();

            // Pastikan direktori thumbnails ada
            $thumbnailDir = public_path('storage/thumbnails');
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0777, true);
            }

            // Pindahkan file secara langsung ke direktori public
            $thumbnailFile->move($thumbnailDir, $thumbnailName);

            // Simpan path relatif untuk diakses via web
            $thumbnailPath = 'thumbnails/' . $thumbnailName;

            // Debug log
            Log::info('Thumbnail berhasil diupload ke: ' . $thumbnailDir . '/' . $thumbnailName);
            Log::info('Path untuk database: ' . $thumbnailPath);

            // Call debug function
            $this->debugImagePath($thumbnailPath);
            
            // Upload digital file ke folder public
            if (!$request->hasFile('digital_file')) {
                throw new \Exception('File digital tidak ditemukan');
            }
            
            // Simpan file digital ke public/storage/digital_files
            $digitalFile = $request->file('digital_file');
            $digitalFileName = time() . '_' . $digitalFile->getClientOriginalName();
            
            // Pastikan direktori digital_files ada
            $digitalDir = public_path('storage/digital_files');
            if (!file_exists($digitalDir)) {
                mkdir($digitalDir, 0777, true);
            }
            
            // Pindahkan file secara langsung ke direktori public
            $digitalFile->move($digitalDir, $digitalFileName);
            
            // Simpan path relatif untuk diakses via web
            $digitalFilePath = 'digital_files/' . $digitalFileName;
            
            Log::info('File digital berhasil diupload ke: ' . $digitalDir . '/' . $digitalFileName);
            Log::info('Digital file display path: ' . $digitalFilePath);

            // Create product
            $product = new Product();
            $product->seller_id = Auth::user()->seller->id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->kategori_id = $request->kategori_id;
            $product->thumbnail = $thumbnailPath; // Simpan path relatif
            $product->digital_file = $digitalFilePath; // Simpan path relatif
            $product->status = $request->status;
            
            $saved = $product->save();
            
            if (!$saved) {
                // Hapus file yang sudah diupload jika gagal menyimpan ke database
                File::delete(public_path('storage/' . $thumbnailPath));
                File::delete(public_path('storage/' . $digitalFilePath));
                throw new \Exception('Gagal menyimpan produk ke database');
            }
            
            Log::info('Produk berhasil disimpan dengan ID: ' . $product->id);
            Log::info('Thumbnail path saved: ' . $product->thumbnail);

            DB::commit();
            
            return redirect()->route('seller.produk')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ' . json_encode($e->errors()));
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa input Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error pada method store: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(string $id)
    {
        $this->checkSeller();
        $this->checkStorageLink();
        
        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);
            $kategoris = Kategori::all();
            
            Log::info('Edit product, thumbnail path: ' . $product->thumbnail);
            Log::info('Full URL: ' . asset('storage/' . $product->thumbnail));
            Log::info('File exists: ' . (File::exists(public_path('storage/' . $product->thumbnail)) ? 'Yes' : 'No'));
            
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
    public function update(Request $request, string $id)
    {
        $this->checkSeller();
        $this->checkStorageLink();
        
        // Log untuk debugging
        Log::info('Method update dipanggil');
        Log::info('Request data:', $request->all());
        
        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);
                
            $request->validate([
                'name' => 'required|string|max:100',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'kategori_id' => 'required|exists:kategoris,id',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'digital_file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
                'status' => 'required|in:draft,published,archived',
            ]);

            Log::info('Validasi update berhasil');
            DB::beginTransaction();
            
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'kategori_id' => $request->kategori_id,
                'status' => $request->status,
            ];

            // Handle thumbnail upload jika ada
            if ($request->hasFile('thumbnail')) {
                // Upload thumbnail baru ke folder public
                $thumbnailFile = $request->file('thumbnail');
                $thumbnailName = time() . '_' . $thumbnailFile->getClientOriginalName();
                
                // Pastikan direktori thumbnails ada
                $thumbnailDir = public_path('storage/thumbnails');
                if (!file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0777, true);
                }
                
                // Pindahkan file secara langsung ke direktori public
                $thumbnailFile->move($thumbnailDir, $thumbnailName);
                
                // Simpan path relatif untuk diakses via web
                $thumbnailPath = 'thumbnails/' . $thumbnailName;
                
                Log::info('Thumbnail baru berhasil diupload ke: ' . $thumbnailDir . '/' . $thumbnailName);
                Log::info('Thumbnail display path: ' . $thumbnailPath);
                
                // Hapus thumbnail lama jika ada
                if ($product->thumbnail && File::exists(public_path('storage/' . $product->thumbnail))) {
                    Log::info('Menghapus thumbnail lama: ' . public_path('storage/' . $product->thumbnail));
                    File::delete(public_path('storage/' . $product->thumbnail));
                }
                
                $data['thumbnail'] = $thumbnailPath;
            }

            // Handle digital file upload jika ada
            if ($request->hasFile('digital_file')) {
                // Upload file digital baru ke folder public
                $digitalFile = $request->file('digital_file');
                $digitalFileName = time() . '_' . $digitalFile->getClientOriginalName();
                
                // Pastikan direktori digital_files ada
                $digitalDir = public_path('storage/digital_files');
                if (!file_exists($digitalDir)) {
                    mkdir($digitalDir, 0777, true);
                }
                
                // Pindahkan file secara langsung ke direktori public
                $digitalFile->move($digitalDir, $digitalFileName);
                
                // Simpan path relatif untuk diakses via web
                $digitalFilePath = 'digital_files/' . $digitalFileName;
                
                Log::info('File digital baru berhasil diupload ke: ' . $digitalDir . '/' . $digitalFileName);
                Log::info('Digital file display path: ' . $digitalFilePath);
                
                // Hapus file digital lama jika ada
                if ($product->digital_file && File::exists(public_path('storage/' . $product->digital_file))) {
                    Log::info('Menghapus file digital lama: ' . public_path('storage/' . $product->digital_file));
                    File::delete(public_path('storage/' . $product->digital_file));
                }
                
                $data['digital_file'] = $digitalFilePath;
            }

            // Update product
            $updated = $product->update($data);
            if (!$updated) {
                throw new \Exception('Gagal memperbarui produk di database');
            }
            
            Log::info('Produk berhasil diperbarui dengan ID: ' . $product->id);
            Log::info('Updated thumbnail path: ' . $product->thumbnail);

            DB::commit();
            
            return redirect()->route('seller.produk')
                ->with('success', 'Produk berhasil diperbarui!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error pada update: ' . json_encode($e->errors()));
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa input Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error pada method update: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        $this->checkSeller();
        
        // Log untuk debugging
        Log::info('Method destroy dipanggil untuk ID: ' . $id);
        
        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);
                
            DB::beginTransaction();
            
            // Hapus file thumbnail jika ada
            if ($product->thumbnail && File::exists(public_path('storage/' . $product->thumbnail))) {
                Log::info('Menghapus thumbnail: ' . public_path('storage/' . $product->thumbnail));
                File::delete(public_path('storage/' . $product->thumbnail));
            }
            
            // Hapus file digital jika ada
            if ($product->digital_file && File::exists(public_path('storage/' . $product->digital_file))) {
                Log::info('Menghapus file digital: ' . public_path('storage/' . $product->digital_file));
                File::delete(public_path('storage/' . $product->digital_file));
            }
            
            // Hapus produk
            $deleted = $product->delete();
            if (!$deleted) {
                throw new \Exception('Gagal menghapus produk dari database');
            }
            
            Log::info('Produk berhasil dihapus dengan ID: ' . $id);
            
            DB::commit();
            
            return redirect()->route('seller.produk')
                ->with('success', 'Produk berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error pada method destroy: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
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