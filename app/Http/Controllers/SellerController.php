<?php
namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{

    public function Index(Request $request)
    {
        $query = Seller::query();

        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        $sort = $request->query('sort', 'latest');
        $query->orderBy('created_at', $sort === 'latest' ? 'desc' : 'asc');

        $sellers = $query->get();
        return view('admin.sellers.index', compact('sellers'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $seller = $user->seller;

        return view('seller.dashboard.index', compact('seller'));
    }

    public function verify($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'active';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller verified successfully!');
    }

    public function deactivate($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'inactive';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller deactivated successfully!');
    }

    public function setPending($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'pending';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller status set to pending!');
    }

    // ----------------------
    // Bagian untuk USER
    // ----------------------

    /**
     * Tampilkan form pendaftaran seller untuk user.
     */
    public function showRegisterForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('status', 'Anda harus login terlebih dahulu untuk mendaftar sebagai seller.');
        }

        $user = Auth::user();
        if ($user->seller) {
            if ($user->seller->status === 'active') {
                return redirect()->route('main.home')->with('status', 'Anda sudah terdaftar sebagai seller aktif.');
            } else {
                return redirect()->route('main.home')->with('status', 'Pendaftaran seller Anda sedang dalam proses verifikasi.');
            }
        }

        return view('main.seller_register');
    }

    public function showRegistrationForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('status', 'Anda harus login terlebih dahulu untuk mendaftar sebagai seller.');
        }

        $user = Auth::user();
        if ($user->seller) {
            if ($user->seller->status === 'active') {
                return redirect()->route('seller.dashboard.index')->with('status', 'Anda sudah terdaftar sebagai seller aktif.');
            } else {
                return redirect()->route('main.home')->with('status', 'Pendaftaran seller Anda sedang dalam proses verifikasi.');
            }
        }

        return view('main.seller_register');
    }

    /**
     * Proses pendaftaran seller oleh user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'contact_email' => 'required|string|email|max:100',
            'contact_whatsapp' => 'required|string|max:20',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('status', 'Anda harus login terlebih dahulu.');
        }

        if ($user->seller) {
            return redirect()->route('main.home')->with('status', 'Anda sudah terdaftar sebagai seller.');
        }

        $profile_image_path = $request->file('profile_image')->store('public/profile_images');
        $banner_image_path = $request->file('banner_image')->store('public/banner_images');

        Seller::create([
            'user_id' => $user->id,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'contact_email' => $request->contact_email,
            'contact_whatsapp' => $request->contact_whatsapp,
            'profile_image' => $profile_image_path,
            'banner_image' => $banner_image_path,
            'status' => 'pending',
        ]);

        return redirect()->route('main.home')->with('status', 'Pendaftaran seller berhasil! Mohon tunggu verifikasi dari admin.');
    }


 public function produkSaya(Request $request)
    {
        $seller = Auth::user()->seller;
        
        $query = Product::where('seller_id', $seller->id)
            ->withTrashed() // Ambil produk yang sudah dihapus
            ->with(['kategori', 'deletion']);

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Urutkan dengan produk yang dihapus admin di atas
        $products = $query->orderByRaw('CASE WHEN deleted_at IS NOT NULL THEN 1 ELSE 0 END DESC')
            ->latest()
            ->paginate(10);

        $kategoris = Kategori::all();

        return view('seller.produk.index', compact('products', 'kategoris'));
    }
}

