<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanCardRequest;
use App\Http\Resources\TransactionResource;
use App\Services\RfidPaymentService;
use Illuminate\Http\JsonResponse;

class RfidPaymentController extends Controller
{
    public function __construct(
        private readonly RfidPaymentService $rfidPaymentService
    ) {
    }

    /**
     * POST /api/rfid/pay
     * Memproses pembayaran menggunakan kartu RFID.
     */
    public function __invoke(ScanCardRequest $request): JsonResponse
    {
        $rfidUid = $request->string('rfid_uid')->toString();
        $amount = (float) $request->input('amount');

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
}
