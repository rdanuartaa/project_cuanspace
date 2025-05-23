<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Notification;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $isSeller = Seller::where('user_id', $user->id)
                ->where('status', 'active')
                ->exists();

            $notificationsQuery = Notification::where(function ($query) use ($user, $isSeller) {
                $query->where('pelaku', 'semua')
                      ->orWhere(function ($q) use ($isSeller) {
                          $q->where('pelaku', $isSeller ? 'seller' : 'pengguna');
                      })
                      ->orWhere(function ($q) use ($user) {
                          $q->where('pelaku', 'khusus')
                            ->where('seller_id', $user->id);
                      });
            })
            ->where('pelaku', '!=', 'promo')
            ->select('id', 'judul', 'pesan', 'pelaku', 'status', 'jadwal_kirim', 'read', 'chat_id', 'seller_id');

            $notifications = $notificationsQuery->paginate(20);

            $formattedNotifications = $notifications->items();

            return response()->json([
                'status' => 'success',
                'data' => $formattedNotifications,
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'last_page' => $notifications->lastPage(),
                    'next_page_url' => $notifications->nextPageUrl(),
                    'prev_page_url' => $notifications->previousPageUrl(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil notifikasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notifikasi tidak ditemukan.',
                ], 404);
            }

            $user = Auth::user();
            $isSeller = Seller::where('user_id', $user->id)->where('status', 'active')->exists();

            if ($notification->pelaku !== 'semua' &&
                $notification->pelaku !== ($isSeller ? 'seller' : 'pengguna') &&
                !($notification->pelaku === 'khusus' && $notification->seller_id === $user->id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak.',
                ], 403);
            }

            $notification->update(['read' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi ditandai sebagai dibaca.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menandai notifikasi sebagai dibaca: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
             $user = User::find(1);
        if ($user && $user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak. Hanya admin yang dapat membuat notifikasi.',
            ], 403);
            }

            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:100',
                'pesan' => 'required|string',
                'pelaku' => 'required|in:semua,pengguna,seller',
                'status' => 'required|in:terkirim,terjadwal,draft',
                'jadwal_kirim' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $notification = Notification::create([
                'admin_id' => $user->id,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
                'pelaku' => $request->pelaku,
                'status' => $request->status,
                'jadwal_kirim' => $request->jadwal_kirim,
                'read' => false,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi berhasil dibuat.',
                'data' => $notification,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat notifikasi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
