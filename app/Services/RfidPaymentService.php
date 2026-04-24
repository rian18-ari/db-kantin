<?php

namespace App\Services;

use App\Models\RfidCard;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RfidPaymentService
{
    /**
     * Process a payment via RFID scan.
     *
     * @param  string $rfidUid
     * @param  float  $amount
     * @return \App\Models\Transaction
     *
     * @throws \Exception
     */
    public function processPayment(string $rfidUid, float $amount): Transaction
    {
        return DB::transaction(function () use ($rfidUid, $amount) {
            /** @var RfidCard|null $card */
            $card = RfidCard::with('user')
                ->where('rfid_uid', $rfidUid)
                ->lockForUpdate()
                ->first();

            if (!$card) {
                throw new \Exception('Kartu RFID tidak ditemukan.', 404);
            }

            if (!$card->isActive()) {
                throw new \Exception('Kartu RFID tidak aktif atau diblokir.', 403);
            }

            $user = $card->user;

            if ($user->balance < $amount) {
                throw new \Exception(
                    sprintf(
                        'Saldo tidak mencukupi. Saldo saat ini: Rp %s, dibutuhkan: Rp %s.',
                        number_format($user->balance, 0, ',', '.'),
                        number_format($amount, 0, ',', '.')
                    ),
                    422
                );
            }

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore - $amount;

            // Deduct user balance
            $user->decrement('balance', $amount);

            // Update last used
            $card->update(['last_used_at' => now()]);

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'rfid_card_id' => $card->id,
                'order_id' => 'PAY-' . strtoupper(Str::random(10)) . '-' . time(),
                'type' => 'payment',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => 'success',
                'payment_method' => 'rfid',
                'notes' => 'Pembayaran kantin via RFID',
                'paid_at' => now(),
            ]);

            return $transaction->load(['user', 'rfidCard']);
        });
    }
}
