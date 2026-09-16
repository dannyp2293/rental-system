<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {

            $table->id();

            $table->string('rental_code')->unique();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            // Karyawan yang membuat/menangani transaksi
            $table->foreignId('employee_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Kurir bersifat opsional
            $table->foreignId('courier_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('rental_start');

            $table->dateTime('rental_end');

            $table->dateTime('returned_at')
                ->nullable();

            $table->decimal('subtotal', 15, 2)
                ->default(0);

            $table->decimal('discount', 15, 2)
                ->default(0);

            $table->decimal('deposit', 15, 2)
                ->default(0);

            $table->decimal('total', 15, 2)
                ->default(0);

            $table->enum('status', [
                'pending',
                'active',
                'returned',
                'cancelled',
            ])->default('pending');

            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};