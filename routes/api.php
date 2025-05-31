<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\APIReviewController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\APISellerController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\TransactionController;


// Route tanpa autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
Route::get('/data', [HomeController::class, 'index']);
Route::get('/trending', [HomeController::class, 'trending']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user', [UserController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/sellers/{id}', [APISellerController::class, 'show']);
    Route::get('kategoris', [KategoriController::class, 'index']);
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::get('/products/{id}/reviews', [APIReviewController::class, 'getReviews']);
    Route::post('/transactions', [TransactionController::class, 'create']);
    Route::get('/transactions/{transactionCode}/download', [TransactionController::class, 'download']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::post('/chats/start', [ChatController::class, 'startChat']);
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/{id}/messages', [ChatController::class, 'messages']);
    Route::post('/chats/{id}/messages', [ChatController::class, 'sendMessage']);
    Route::get('/checkout/{productId}', [MainController::class, 'checkout']);
    Route::post('/process-checkout/{productId}', [MainController::class, 'processCheckout']);
    Route::get('/download-now/{productId}', [MainController::class, 'downloadNow']);
    Route::post('/accept-agree/{productId}', [MainController::class, 'acceptAgreement']);
    Route::get('/download/{productId}', [MainController::class, 'download']);
    Route::get('/order-history', [MainController::class, 'orderHistory']);
    Route::post('/submit-review/{productId}', [MainController::class, 'submitReview']);
});
