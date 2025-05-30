<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class APISellerController extends Controller
{
    public function show($id)
    {
        try {
            $seller = Seller::find($id); // Gunakan id seller
            if (!$seller) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Seller tidak ditemukan.',
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $seller->id,
                    'user_id' => $seller->user_id, // Tambahkan user_id
                    'brand_name' => $seller->brand_name,
                    'description' => $seller->description,
                    'address' => $seller->address ?? '',
                    'contact_email' => $seller->contact_email,
                    'contact_whatsapp' => $seller->contact_whatsapp,
                    'profile_image' => $seller->profile_image ? url('storage/seller/profile/' . $seller->profile_image) : null,
                    'banner_image' => $seller->banner_image ? url('storage/seller/banner/' . $seller->banner_image) : null,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
