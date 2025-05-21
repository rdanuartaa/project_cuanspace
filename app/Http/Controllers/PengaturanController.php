<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $seller = Seller::where('user_id', Auth::id())->firstOrFail();
        return view('seller.pengaturan.index', compact('seller'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'contact_email' => 'required|email|max:100',
            'contact_whatsapp' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $seller = Seller::where('user_id', Auth::id())->firstOrFail();

        $seller->brand_name = $request->brand_name;
        $seller->description = $request->description;
        $seller->contact_email = $request->contact_email;
        $seller->contact_whatsapp = $request->contact_whatsapp;

        if ($request->hasFile('profile_image')) {
            if ($seller->profile_image) {
                Storage::delete($seller->profile_image);
            }
            $seller->profile_image = $request->file('profile_image')->store('profile_images');
        }

        if ($request->hasFile('banner_image')) {
            if ($seller->banner_image) {
                Storage::delete($seller->banner_image);
            }
            $seller->banner_image = $request->file('banner_image')->store('banner_images');
        }

        $seller->save();

        return back()->with('success', 'Pengaturan toko berhasil diperbarui.');
    }
}
