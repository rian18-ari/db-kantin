<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private readonly MidtransService $midtransService
    ) {
    }

    /**
     * POST /api/webhook/midtrans
     * Menerima notifikasi payment dari Midtrans dan mengupdate saldo user.
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('[Midtrans Webhook] Received notification', ['payload' => $payload]);

        // 1. Verify signature
        if (!$this->midtransService->verifySignature($payload)) {
            Log::warning('[Midtrans Webhook] Invalid signature', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'order_id missing.'], 400);
        }

        /** @var Transaction|null $transaction */
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::warning('[Midtrans Webhook] Transaction not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        // 2. Idempotency guard — skip if already settled
        if ($transaction->status === 'success') {
            return response()->json(['message' => 'Already processed.'], 200);
        }

        // 3. Determine final status
        $isSettled = $this->isSettled($transactionStatus, $fraudStatus);
        $isFailed = $this->isFailed($transactionStatus);

        DB::transaction(function () use ($transaction, $payload, $transactionStatus, $isSettled, $isFailed) {
            if ($isSettled) {
                /** @var \App\Models\User $user */
                $user = \App\Models\User::where('id', $transaction->user_id)->lockForUpdate()->firstOrFail();
                $balanceBefore = (float) $user->balance;
                $balanceAfter = $balanceBefore + (float) $transaction->amount;

                $user->increment('balance', (float) $transaction->amount);

                $transaction->update([
                    'status' => 'success',
                    'payment_method' => $payload['payment_type'] ?? null,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'midtrans_payload' => $payload,
                    'paid_at' => now(),
                ]);

                Log::info('[Midtrans Webhook] Top-up settled', [
                    'order_id' => $transaction->order_id,
                    'user_id' => $user->id,
                    'amount' => $transaction->amount,
                    'balance_after' => $balanceAfter,
                ]);

            } elseif ($isFailed) {
                $transaction->update([
                    'status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
                    'midtrans_payload' => $payload,
                ]);

                Log::info('[Midtrans Webhook] Top-up failed/expired', [
                    'order_id' => $transaction->order_id,
                    'status' => $transactionStatus,
                ]);
            }
        });

        return response()->json(['message' => 'Notification processed.'], 200);
    }

    /**
     * Check if transaction is settled (payment success).
     */
    private function isSettled(?string $transactionStatus, ?string $fraudStatus): bool
    {
        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            return true;
        }

        return $transactionStatus === 'settlement';
    }

    /**
     * Check if transaction is failed or expired.
     */
    private function isFailed(?string $transactionStatus): bool
    {
        return in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true);
    }
}
