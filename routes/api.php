<?php

use App\Http\Controllers\Api\ScanCardController;
use App\Http\Controllers\Api\TopUpController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — RFID Kantin Management System
|--------------------------------------------------------------------------
*/

/*
 * Webhook Midtrans — harus dikecualikan dari CSRF & middleware auth.
 * Midtrans memanggil endpoint ini langsung dari server mereka.
 */
Route::post('/webhook/midtrans', [WebhookController::class, 'handle'])
    ->name('webhook.midtrans');

/*
 * Top-up saldo via Midtrans Snap.
 * POST /api/topup
 * Body: { user_id, amount }
 */
Route::post('/topup', TopUpController::class)
    ->name('topup.create');

/*
 * Scan kartu RFID untuk pembayaran kantin.
 * POST /api/scan-card
 * Body: { rfid_uid, amount }
 */
Route::post('/scan-card', ScanCardController::class)
    ->name('card.scan');

/*
 * ─────────────────────────────────────────────────────────
 * Contoh route tambahan (bisa dilindungi dengan auth:sanctum)
 * ─────────────────────────────────────────────────────────
 */
Route::middleware('auth:sanctum')->group(function () {
    // Profil pengguna terautentikasi
    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user());
    })->name('user.profile');
});
