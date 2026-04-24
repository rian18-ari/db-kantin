<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('rfid_card_id')->nullable()->constrained('rfid_cards')->nullOnDelete();
            $table->string('order_id')->unique()->comment('Unique order ID sent to Midtrans or generated internally');
            $table->enum('type', ['topup', 'payment'])->comment('topup = saldo masuk, payment = pembayaran kantin');
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2)->comment('Saldo sebelum transaksi');
            $table->decimal('balance_after', 12, 2)->comment('Saldo setelah transaksi');
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('payment_method')->nullable()->comment('qris, gopay, bank_transfer, etc.');
            $table->json('midtrans_payload')->nullable()->comment('Raw payload dari Midtrans notification');
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
