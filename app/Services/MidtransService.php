<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create a Snap payment URL for top-up.
     *
     * @param  User  $user
     * @param  float $amount
     * @return array{snap_token: string, redirect_url: string, order_id: string}
     */
    public function createTopUpTransaction(User $user, float $amount): array
    {
        $orderId = 'TOPUP-' . strtoupper(Str::random(12)) . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'TOPUP',
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Top-up Saldo Kantin',
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $redirectUrl = Snap::getSnapUrl($params);

        // Record pending transaction
        Transaction::create([
            'user_id' => $user->id,
            'rfid_card_id' => null,
            'order_id' => $orderId,
            'type' => 'topup',
            'amount' => $amount,
            'balance_before' => $user->balance,
            'balance_after' => $user->balance, // will be updated on webhook settlement
            'status' => 'pending',
            'notes' => 'Top-up via Midtrans Snap',
        ]);

        return [
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
            'order_id' => $orderId,
        ];
    }

    /**
     * Verify the Midtrans notification signature.
     *
     * @param  array $payload
     * @return bool
     */
    public function verifySignature(array $payload): bool
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return $expected === ($payload['signature_key'] ?? '');
    }
}
