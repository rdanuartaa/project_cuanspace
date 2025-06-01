<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Validation\ValidationException;

class SellerController extends Controller
{
    /**
     * Show the seller registration form.
     */
    public function showRegistrationForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
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
     * Handle seller registration.
     */
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'contact_email' => 'required|email|max:100',
            'contact_whatsapp' => 'required|string|max:20',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        if ($user->seller) {
            return redirect()->route('main.home')->with('error', 'Anda sudah terdaftar sebagai seller.');
        }

        try {
            DB::beginTransaction();

            // Simpan profile image
            $profileImageName = Str::uuid() . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->storeAs('seller/profile', $profileImageName, 'public');

            // Simpan banner image
            $bannerImageName = Str::uuid() . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->storeAs('seller/banner', $bannerImageName, 'public');

            Seller::create([
                'user_id' => $user->id,
                'brand_name' => $request->brand_name,
                'description' => $request->description,
                'contact_email' => $request->contact_email,
                'contact_whatsapp' => $request->contact_whatsapp,
                'profile_image' => $profileImageName,
                'banner_image' => $bannerImageName,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('main.home')->with('success', 'Pendaftaran seller berhasil! Mohon tunggu verifikasi dari admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan seller: ' . $e->getMessage());
        }
    }

    /**
     * Show seller dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $seller = $user->seller;

        if (!$seller) {
            return redirect()->route('main.home')->with('error', 'Anda belum terdaftar sebagai seller.');
        }

        // Total produk yang dipublikasikan
        $totalProduk = Product::where('seller_id', $seller->id)
            ->where('status', 'published')
            ->count();

        // Total transaksi berhasil
        $totalTransaksiBerhasil = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('transactions.status', 'paid')
            ->count();

        // Rating rata-rata toko
        $ratingToko = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->avg('reviews.rating');

        // Produk baru (5 produk terakhir)
        $produkBaru = Product::where('seller_id', $seller->id)
            ->where('status', 'published')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Total penghasilan dari transaksi sukses
        $totalPenghasilan = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('transactions.status', 'paid')
            ->sum('transactions.amount');

        // 🔁 Tambahkan Penghasilan Pending
        $totalPenghasilanPending = DB::table('transactions')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->where('products.seller_id', $seller->id)
            ->where('transactions.status', 'pending')
            ->sum('transactions.amount');

        // Saldo seller
        $totalSaldo = $seller->balance ?? 0;

        // Kirim semua data ke view
        return view('seller.dashboard.index', compact(
            'seller',
            'totalProduk',
            'totalTransaksiBerhasil',
            'ratingToko',
            'produkBaru',
            'totalSaldo',
            'totalPenghasilan',
            'totalPenghasilanPending' // ✅ Tambahkan variabel ini
        ));
    }

    public function index(Request $request)
    {
        $query = Seller::query();

        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        $sellers = $query->get();
        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Admin: Filter sellers by status (AJAX)
     */
    public function filter(Request $request)
    {
        $query = Seller::query();

        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        $sellers = $query->get();
        $html = view('admin.sellers._table_body', compact('sellers'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Admin: Verify seller
     */
    public function verify($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'active';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller berhasil diverifikasi!');
    }

    /**
     * Admin: Deactivate seller
     */
    public function deactivate($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'inactive';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller berhasil dinonaktifkan!');
    }

    /**
     * Admin: Set seller to pending
     */
    public function setPending($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'pending';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Status seller diubah menjadi pending!');
    }

    /**
     * Show edit form for seller profile.
     */
    public function edit()
    {
        $user = Auth::user();
        if (!$user || !$user->seller) {
            return redirect()->route('main.home')->with('error', 'Tidak ada data seller ditemukan.');
        }

        $seller = $user->seller;
        return view('seller.profile.edit', compact('seller'));
    }

    /**
     * Update seller profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->seller) {
            return redirect()->route('main.home')->with('error', 'Tidak ada data seller ditemukan.');
        }

        $seller = $user->seller;

        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'contact_email' => 'required|email|max:100',
            'contact_whatsapp' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Update data seller
            $seller->brand_name = $request->brand_name;
            $seller->description = $request->description;
            $seller->contact_email = $request->contact_email;
            $seller->contact_whatsapp = $request->contact_whatsapp;

            // Handle profile image
            if ($request->hasFile('profile_image')) {
                if ($seller->profile_image) {
                    Storage::disk('public')->delete('seller/profile/' . $seller->profile_image);
                }
                $profileImageName = Str::uuid() . '.' . $request->file('profile_image')->getClientOriginalExtension();
                $request->file('profile_image')->storeAs('seller/profile', $profileImageName, 'public');
                $seller->profile_image = $profileImageName;
            }

            // Handle banner image
            if ($request->hasFile('banner_image')) {
                if ($seller->banner_image) {
                    Storage::disk('public')->delete('seller/banner/' . $seller->banner_image);
                }
                $bannerImageName = Str::uuid() . '.' . $request->file('banner_image')->getClientOriginalExtension();
                $request->file('banner_image')->storeAs('seller/banner', $bannerImageName, 'public');
                $seller->banner_image = $bannerImageName;
            }

            $seller->save();
            DB::commit();

            return redirect()->route('seller.dashboard.index')->with('success', 'Profil seller berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil seller: ' . $e->getMessage());
        }
    }

    /**
     * Show seller's products (produkSaya).
     */
    public function produkSaya(Request $request)
    {
        $seller = Auth::user()->seller;

        $query = Product::where('seller_id', $seller->id)
            ->withTrashed()
            ->with(['kategori', 'deletion']);

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderByRaw('CASE WHEN deleted_at IS NOT NULL THEN 1 ELSE 0 END DESC')
            ->latest()
            ->paginate(15);

        $kategoris = Kategori::all();

        return view('seller.produk.index', compact('products', 'kategoris'));
    }
}
