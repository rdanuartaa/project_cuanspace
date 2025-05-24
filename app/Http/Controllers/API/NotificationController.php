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
                $query->where('penerima', 'semua')
                      ->orWhere(function ($q) use ($isSeller) {
                          $q->where('penerima', $isSeller ? 'seller' : 'pengguna');
                      })
                      ->orWhere(function ($q) use ($user) {
                          $q->where('penerima', 'khusus')
                            ->where(function ($subQuery) use ($user) {
                                $subQuery->where('user_id', $user->id)
                                         ->orWhere('seller_id', $user->id);
                            });
                      });
            })
            ->select('id', 'judul', 'pesan', 'penerima', 'status', 'read', 'chat_id', 'seller_id', 'user_id', 'created_at');

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

            if ($notification->penerima !== 'semua' &&
                $notification->penerima !== ($isSeller ? 'seller' : 'pengguna') &&
                !($notification->penerima === 'khusus' && ($notification->user_id === $user->id || $notification->seller_id === $user->id))) {
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
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            // Pastikan user adalah admin
            $admin = \App\Models\Admin::where('email', $user->email)->first();
            if (!$admin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akses ditolak. Hanya admin yang dapat membuat notifikasi.',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:100',
                'pesan' => 'required|string',
                'penerima' => 'required|in:semua,pengguna,seller,khusus',
                'status' => 'required|in:terkirim',
                'user_id' => 'nullable|exists:users,id',
                'seller_id' => 'nullable|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            if ($request->penerima === 'khusus') {
                if ($request->user_id && $request->seller_id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pilih hanya salah satu: user atau seller untuk notifikasi khusus.',
                    ], 422);
                }
                if (!$request->user_id && !$request->seller_id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pilih user atau seller untuk notifikasi khusus.',
                    ], 422);
                }
            } else {
                $request->merge(['user_id' => null, 'seller_id' => null]);
            }

            $notification = Notification::create([
                'admin_id' => $admin->id,
                'judul' => $request->judul,
                'pesan' => $request->pesan,
                'penerima' => $request->penerima,
                'user_id' => $request->user_id,
                'seller_id' => $request->seller_id,
                'status' => $request->status,
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
