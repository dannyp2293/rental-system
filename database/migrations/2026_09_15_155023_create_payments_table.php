<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_id')
                ->constrained('rentals')
                ->cascadeOnDelete();

            $table->string('payment_code')
                ->unique();

            $table->dateTime('paid_at');

            $table->decimal('amount', 15, 2);

            $table->enum('method', [
                'cash',
                'transfer',
                'qris',
                'debit',
                'e_wallet',
            ]);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('paid_at');
            $table->index('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};