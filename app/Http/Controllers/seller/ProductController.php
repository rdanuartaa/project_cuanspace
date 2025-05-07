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
            $query = Product::query();
            
            // Always filter products by current seller
            $query->where('seller_id', Auth::user()->seller->id);
            
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
            
            return view('seller.produk', compact('products', 'kategoris'));
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
            
            return view('seller.tambah_produk', compact('kategoris'));
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
        // Log untuk debugging
        Log::info('Method store dipanggil');
        Log::info('Request data:', $request->all());
        
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
            
            Log::info('Validasi berhasil');

            DB::beginTransaction();
            
            // Pastikan seller_id ada
            if (!Auth::user()->seller->id) {
                throw new \Exception('ID seller tidak ditemukan');
            }
            
            // Upload thumbnail
            if (!$request->hasFile('thumbnail')) {
                throw new \Exception('File thumbnail tidak ditemukan');
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails');
            if (!$thumbnailPath) {
                throw new \Exception('Gagal mengupload thumbnail');
            }
            
            Log::info('Thumbnail berhasil diupload: ' . $thumbnailPath);
            
            // Upload digital file
            if (!$request->hasFile('digital_file')) {
                throw new \Exception('File digital tidak ditemukan');
            }
            
            $digitalFilePath = $request->file('digital_file')->store('public/digital_files');
            if (!$digitalFilePath) {
                Storage::delete($thumbnailPath);
                throw new \Exception('Gagal mengupload file digital');
            }
            
            Log::info('File digital berhasil diupload: ' . $digitalFilePath);

            // Create product
            $product = new Product();
            $product->seller_id = Auth::user()->seller->id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->kategori_id = $request->kategori_id;
            $product->thumbnail = str_replace('public/', '', $thumbnailPath);
            $product->digital_file = str_replace('public/', '', $digitalFilePath);
            $product->status = $request->status;
            
            $saved = $product->save();
            
            if (!$saved) {
                throw new \Exception('Gagal menyimpan produk ke database');
            }
            
            Log::info('Produk berhasil disimpan dengan ID: ' . $product->id);

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
        
        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);
            $kategoris = Kategori::all();
            
            return view('seller.edit_produk', compact('product', 'kategoris'));
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
        // Log untuk debugging
        Log::info('Method update dipanggil');
        Log::info('Request data:', $request->all());
        
        $this->checkSeller();
        
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

            // Handle thumbnail upload if provided
            if ($request->hasFile('thumbnail')) {
                // Upload new thumbnail
                $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails');
                if (!$thumbnailPath) {
                    throw new \Exception('Gagal mengupload thumbnail baru.');
                }
                
                Log::info('Thumbnail baru berhasil diupload: ' . $thumbnailPath);
                
                // Delete old thumbnail
                if ($product->thumbnail) {
                    Storage::delete('public/' . $product->thumbnail);
                }
                
                $data['thumbnail'] = str_replace('public/', '', $thumbnailPath);
            }

            // Handle digital file upload if provided
            if ($request->hasFile('digital_file')) {
                // Upload new file
                $digitalFilePath = $request->file('digital_file')->store('public/digital_files');
                if (!$digitalFilePath) {
                    throw new \Exception('Gagal mengupload file digital baru.');
                }
                
                Log::info('File digital baru berhasil diupload: ' . $digitalFilePath);
                
                // Delete old file
                if ($product->digital_file) {
                    Storage::delete('public/' . $product->digital_file);
                }
                
                $data['digital_file'] = str_replace('public/', '', $digitalFilePath);
            }

            // Update product
            $updated = $product->update($data);
            if (!$updated) {
                throw new \Exception('Gagal memperbarui produk di database');
            }
            
            Log::info('Produk berhasil diperbarui dengan ID: ' . $product->id);

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
        // Log untuk debugging
        Log::info('Method destroy dipanggil untuk ID: ' . $id);
        
        $this->checkSeller();
        
        try {
            $product = Product::where('seller_id', Auth::user()->seller->id)
                ->findOrFail($id);
                
            DB::beginTransaction();
            
            // Delete files
            if ($product->thumbnail) {
                Storage::delete('public/' . $product->thumbnail);
                Log::info('Thumbnail dihapus: ' . $product->thumbnail);
            }
            
            if ($product->digital_file) {
                Storage::delete('public/' . $product->digital_file);
                Log::info('File digital dihapus: ' . $product->digital_file);
            }
            
            // Delete product
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
}