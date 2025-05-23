<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\APISellerController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('/data', [HomeController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user', [UserController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/sellers/{id}', [APISellerController::class, 'show']);
    Route::get('kategoris', [KategoriController::class, 'index']);
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);

    // Rute untuk notifikasi
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications', [NotificationController::class, 'store']);

    // Rute untuk chat
    Route::post('/chats/start', [ChatController::class, 'startChat']); // Rute baru untuk memulai percakapan
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/{id}/messages', [ChatController::class, 'messages']);
    Route::post('/chats/{id}/messages', [ChatController::class, 'sendMessage']);
});
