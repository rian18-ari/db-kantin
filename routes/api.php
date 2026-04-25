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
 * Scan kartu RFID untuk pendaftaran (Pending status).
 * POST /api/rfid/register
 */
Route::post('/rfid/register', \App\Http\Controllers\Api\RfidRegistrationController::class)
    ->name('api.rfid.register');

/*
 * Scan kartu RFID untuk pembayaran.
 * POST /api/rfid/pay
 */
Route::post('/rfid/pay', \App\Http\Controllers\Api\RfidPaymentController::class)
    ->name('api.rfid.pay');

/*
 * Scan kartu RFID (Smart Endpoint - Mendukung auto-switch mode).
 * POST /api/scan-card
 * Body: { rfid_uid, amount, mode? }
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
