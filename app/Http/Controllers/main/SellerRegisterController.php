<?php
// app/Http/Controllers/Main/SellerRegisterController.php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Seller;

class SellerRegisterController extends Controller
{
    public function showForm()
    {
        // Cek apakah user sudah terdaftar sebagai seller
        $user = Auth::user();
        if ($user->seller) {
            return redirect()->route('seller.dashboard')->with('status', 'You are already a seller!');
        }

        return view('main.seller_register');
    }

    public function register(Request $request)
    {
        // Validasi input pendaftaran seller
        $request->validate([
            'brand_name' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'contact_email' => 'required|string|email|max:100',
            'contact_whatsapp' => 'required|string|max:20',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        // Cek jika user sudah menjadi seller
        if ($user->seller) {
            return redirect()->route('main.home')->with('status', 'You are already a seller!');
        }

        // Mengambil file gambar
        $profile_image_path = $request->file('profile_image')->store('public/profile_images');
        $banner_image_path = $request->file('banner_image')->store('public/banner_images');

        // Simpan data seller ke tabel sellers
        $seller = new Seller();
        $seller->user_id = $user->id;
        $seller->brand_name = $request->brand_name;
        $seller->description = $request->description;
        $seller->contact_email = $request->contact_email;
        $seller->contact_whatsapp = $request->contact_whatsapp;
        $seller->profile_image = $profile_image_path;
        $seller->banner_image = $banner_image_path;
        $seller->status = 'pending'; // Status awal seller adalah 'pending'
        $seller->save();

        return redirect()->route('main.home')->with('status', 'You have registered as a seller. Your status is pending.');
    }
}



