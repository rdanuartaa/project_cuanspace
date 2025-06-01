<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                ], 401);
            }
            $user->load('userDetail');
            return response()->json([
                'success' => true,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching user: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengguna: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Tambahkan fungsi profile untuk endpoint /api/user/profile
    public function profile(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                ], 401);
            }
            $user->load('userDetail');
            return response()->json([
                'success' => true,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching user profile: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil profil pengguna: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'date_of_birth' => 'nullable|date',
            'religion' => 'nullable|in:Islam,Kristen,Hindu,Buddha,Konghucu,Lainnya',
            'status' => 'nullable|in:Pelajar,Mahasiswa,Pekerja,Wirausaha,Lainnya',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Perbarui data user
        $user->update($request->only('name', 'email'));

        // Perbarui atau buat data user_details
        $userDetailData = $request->only([
            'phone',
            'address',
            'gender',
            'date_of_birth',
            'religion',
            'status',
        ]);

        // Tangani unggah foto profil
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->userDetail && $user->userDetail->profile_photo) {
                Storage::disk('public')->delete($user->userDetail->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $userDetailData['profile_photo'] = $path;
        }

        $user->userDetail()->updateOrCreate(
            ['user_id' => $user->id],
            $userDetailData
        );

        // Muat ulang user dengan relasi userDetail
        $user->load('userDetail');

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }
}
