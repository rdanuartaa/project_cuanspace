<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function create(Request $request)
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
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            $product = Product::find($request->product_id);
            $totalPrice = $product->price * $request->quantity;
            $transactionCode = 'TRX-' . time();

            // Placeholder: Integrasi Midtrans untuk snap token
            $snapToken = 'dummy-snap-token-' . time();

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'transaction_code' => $transactionCode,
                'amount' => $totalPrice,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'download_count' => 0,
            ]);

            Log::info("Transaksi dibuat: transaction_code={$transactionCode}, user_id={$user->id}");

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dibuat.',
                'data' => [
                    'transaction_code' => $transactionCode,
                    'snap_token' => $snapToken,
                    'amount' => $totalPrice,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error("Kesalahan saat membuat transaksi: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function download(Request $request, $transactionCode)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->first();

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaksi tidak ditemukan atau belum selesai.',
                ], 404);
            }

            $product = $transaction->product;
            if (!$product->digital_file) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File digital tidak tersedia.',
                ], 404);
            }

            $filePath = 'digital_files/' . $product->digital_file;
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tidak ditemukan.',
                ], 404);
            }

            $transaction->increment('download_count');
            Log::info("File diunduh: transaction_code={$transactionCode}, user_id={$user->id}");

            return Storage::disk('public')->download($filePath);
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengunduh file: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
