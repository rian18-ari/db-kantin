<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopUpRequest;
use App\Http\Resources\TransactionResource;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;

class TopUpController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtransService
    ) {
    }

    /**
     * POST /api/topup
     * Membuat transaksi top-up dan mengembalikan Snap token Midtrans.
     */
    public function __invoke(TopUpRequest $request): JsonResponse
    {
        $user = User::findOrFail($request->integer('user_id'));
        $amount = (float) $request->input('amount');

        try {
            $result = $this->midtransService->createTopUpTransaction($user, $amount);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi top-up berhasil dibuat.',
                'data' => [
                    'snap_token' => $result['snap_token'],
                    'redirect_url' => $result['redirect_url'],
                    'order_id' => $result['order_id'],
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi Midtrans: ' . $e->getMessage(),
            ], 500);
        }
    }
}
