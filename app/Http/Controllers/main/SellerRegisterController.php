<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Seller;

class SellerRegisterController extends Controller
{
    /**
     * Show the seller registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        // Check if user is already authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('status', 'Anda harus login terlebih dahulu untuk mendaftar sebagai seller.');
        }

        // Check if user already registered as seller
        $user = Auth::user();
        if ($user->seller) {
            if ($user->seller->status === 'active') {
                return redirect()->route('seller.dashboard')->with('status', 'Anda sudah terdaftar sebagai seller aktif.');
            } else {
                return redirect()->route('main.home')->with('status', 'Pendaftaran seller Anda sedang dalam proses verifikasi. Mohon tunggu konfirmasi dari admin.');
            }
        }

        return view('main.seller_register');
    }


    public function register(Request $request)
    {
        // Validate the registration input
        $request->validate([
            'brand_name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'contact_email' => 'required|string|email|max:100',
            'contact_whatsapp' => 'required|string|max:20',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('status', 'Anda harus login terlebih dahulu untuk mendaftar sebagai seller.');
        }

        // Check if user already registered as seller
        if ($user->seller) {
            return redirect()->route('main.home')->with('status', 'Anda sudah terdaftar sebagai seller.');
        }

        // Store image files
        $profile_image_path = $request->file('profile_image')->store('public/profile_images');
        $banner_image_path = $request->file('banner_image')->store('public/banner_images');

        // Create new seller record
        $seller = new Seller();
        $seller->user_id = $user->id;
        $seller->brand_name = $request->brand_name;
        $seller->description = $request->description;
        $seller->contact_email = $request->contact_email;
        $seller->contact_whatsapp = $request->contact_whatsapp;
        $seller->profile_image = $profile_image_path;
        $seller->banner_image = $banner_image_path;
        $seller->status = 'pending'; // Default status for new sellers
        $seller->save();

        return redirect()->route('main.home')->with('status', 'Pendaftaran seller berhasil! Status Anda saat ini adalah pending. Mohon tunggu verifikasi dari admin.');
    }
}