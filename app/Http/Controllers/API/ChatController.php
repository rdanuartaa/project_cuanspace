<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    // Fungsi untuk memulai percakapan baru
    public function startChat(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'seller_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $sellerId = $request->input('seller_id');

            // Pastikan seller_id benar-benar milik seller
            $seller = Seller::where('user_id', $sellerId)->first();
            if (!$seller) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna yang dipilih bukan seller.',
                ], 404);
            }

            // Cek apakah sudah ada percakapan antara user dan seller
            $chat = Chat::where('user_id', $user->id)
                ->where('seller_id', $sellerId)
                ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'user_id' => $user->id,
                    'seller_id' => $sellerId,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Percakapan berhasil dimulai.',
                'data' => [
                    'chat_id' => $chat->id,
                    'seller_id' => $chat->seller_id,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memulai percakapan: ' . $e->getMessage(),
            ], 500);
        }
    }

  public function index()
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak terautentikasi.',
            ], 401);
        }

        $chats = Chat::where('user_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['user', 'seller', 'messages' => function ($query) {
                $query->latest()->first();
            }])
            ->get()
            ->map(function ($chat) {
                $seller = Seller::where('user_id', $chat->seller_id)->first();
                $lastMessage = $chat->messages->first();
                return [
                    'id' => $chat->id,
                    'seller_id' => $chat->seller_id,
                    'seller_name' => $seller ? $seller->brand_name : ($chat->seller ? $chat->seller->name : 'Nama Tidak Diketahui'),
                    'last_message' => $lastMessage ? $lastMessage->content : '',
                    'last_message_time' => $lastMessage ? $lastMessage->created_at->toDateTimeString() : '',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $chats,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat mengambil daftar percakapan: ' . $e->getMessage(),
        ], 500);
    }
}
    public function messages($id)
{
    try {
        $user = Auth::user();
        $chat = Chat::find($id);

        if (!$chat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Percakapan tidak ditemukan.',
            ], 404);
        }

        if ($chat->user_id !== $user->id && $chat->seller_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $messages = Message::where('chat_id', $id)
            ->select('id', 'sender_id', 'content', 'created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat mengambil pesan: ' . $e->getMessage(),
        ], 500);
    }
}

    public function sendMessage(Request $request, $id)
{
    try {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = Auth::user();
        $chat = Chat::find($id);

        if (!$chat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Percakapan tidak ditemukan.',
            ], 404);
        }

        if ($chat->user_id !== $user->id && $chat->seller_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $message = Message::create([
            'chat_id' => $id,
            'sender_id' => $user->id,
            'content' => $request->content,
        ]);

        $recipientId = $chat->user_id === $user->id ? $chat->seller_id : $chat->user_id;
        Notification::create([
            'admin_id' => null, // Set ke null karena tidak diperlukan
            'judul' => 'Pesan Baru',
            'pesan' => 'Anda menerima pesan baru dari ' . $user->name,
            'pelaku' => 'khusus',
            'status' => 'terkirim',
            'jadwal_kirim' => now(),
            'read' => false,
            'chat_id' => $chat->id,
            'seller_id' => $recipientId,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim.',
            'data' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'content' => $message->content,
                'created_at' => $message->created_at->toDateTimeString(),
            ],
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat mengirim pesan: ' . $e->getMessage(),
        ], 500);
    }
}
}
