<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
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
            Log::info("Mencoba memulai percakapan dengan seller_id: $sellerId");

            $seller = Seller::where('user_id', $sellerId)->first();
            if (!$seller) {
                Log::warning("Tidak ada penjual ditemukan untuk user_id: $sellerId");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna yang dipilih bukan penjual.',
                ], 404);
            }

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
                    'seller_name' => $seller->brand_name ?? 'Penjual Tidak Diketahui',
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error("Kesalahan memulai percakapan: " . $e->getMessage());
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
                    $query->orderBy('created_at', 'desc')->first();
                }])
                ->get();

            Log::info('Chats retrieved for user ' . $user->id . ': ' . $chats->toJson());

            $chats = $chats->map(function ($chat) use ($user) {
                $seller = Seller::where('user_id', $chat->seller_id)->first();
                $lastMessage = $chat->messages->first();
                $sender = $lastMessage ? User::find($lastMessage->sender_id) : null;
                $senderName = $sender ? $sender->name : ($chat->user_id == $user->id ? $user->name : ($seller ? $seller->brand_name : 'Penjual Tidak Diketahui'));
                return [
                    'id' => $chat->id,
                    'seller_id' => $chat->seller_id,
                    'seller_name' => $seller ? $seller->brand_name : 'Penjual Tidak Diketahui',
                    'last_message' => $lastMessage ? $lastMessage->content : 'Belum ada pesan',
                    'last_message_time' => $lastMessage ? $lastMessage->created_at->toIso8601String() : now()->toIso8601String(),
                    'sender_name' => $senderName,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $chats,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengambil daftar percakapan: " . $e->getMessage());
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
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'content' => $message->content,
                        'created_at' => $message->created_at->toIso8601String(),
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengambil pesan: " . $e->getMessage());
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
            $isRecipientSeller = Seller::where('user_id', $recipientId)->exists();
            Notification::create([
                'admin_id' => 1,
                'judul' => 'Pesan Baru',
                'pesan' => 'Anda menerima pesan baru dari ' . $user->name,
                'penerima' => 'khusus',
                'status' => 'terkirim',
                'read' => false,
                'chat_id' => $chat->id,
                'seller_id' => $isRecipientSeller ? $recipientId : null,
                'user_id' => $isRecipientSeller ? null : $recipientId,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesan berhasil dikirim.',
                'data' => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->toIso8601String(),
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengirim pesan: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengirim pesan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
