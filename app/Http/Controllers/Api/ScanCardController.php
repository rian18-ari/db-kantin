<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanCardRequest;
use App\Http\Resources\TransactionResource;
use App\Services\RfidPaymentService;
use Illuminate\Http\JsonResponse;

class ScanCardController extends Controller
{
    public function __construct(
        private readonly RfidPaymentService $rfidPaymentService
    ) {
    }

    /**
     * POST /api/scan-card
     * Menerima rfid_uid dan amount, memvalidasi saldo, dan mencatat transaksi pembayaran.
     */
    public function __invoke(ScanCardRequest $request): JsonResponse
    {
        $rfidUid = $request->string('rfid_uid')->toString();
        $amount = (float) $request->input('amount', 0);
        $mode = $request->string('mode', 'payment')->toString();

        // Cek apakah server sedang dalam mode registrasi (via Cache)
        // atau request secara eksplisit meminta registrasi
        $isRegistrationMode = \Illuminate\Support\Facades\Cache::get('rfid_registration_mode', false) || $mode === 'registration';

        if ($isRegistrationMode) {
            return $this->handleRegistration($rfidUid);
        }

        try {
            $transaction = $this->rfidPaymentService->processPayment($rfidUid, $amount);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil.',
                'data' => new TransactionResource($transaction),
            ], 200);

        } catch (\Exception $e) {
            $statusCode = match ($e->getCode()) {
                404 => 404,
                403 => 403,
                422 => 422,
                default => 500,
            };

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Handle registration mode logic.
     */
    private function handleRegistration(string $rfidUid): JsonResponse
    {
        $card = \App\Models\RfidCard::where('rfid_uid', $rfidUid)->first();

        if ($card) {
            if ($card->status === 'pending') {
                return response()->json([
                    'success' => true,
                    'message' => 'Kartu sudah terdaftar sebagai pending.',
                    'status' => 'pending',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Kartu sudah terdaftar dan aktif.',
                'status' => $card->status,
            ], 422);
        }

        \App\Models\RfidCard::create([
            'rfid_uid' => $rfidUid,
            'status' => 'pending',
            'user_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kartu baru berhasil disimpan sebagai pending.',
            'status' => 'pending',
        ], 201);
    }
}
